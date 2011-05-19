<?php
class DpiBehaviorSluggable extends Behavior
{
    // default parameters
    protected $parameters = array(
        'column'            => null,
        'slug_column'       => 'slug',
        'keep_letter_case'  => 'false',
        'separator'         => '-',
        'permanent'         => 'false',
        'is_i18n'           => 'false',
        'i18n_column'       => 'culture',
    );

    /**
     * Add the slug_column to the current table
     */
    public function modifyTable()
    {
        $table = $this->getTable();
        $columnName = $this->getParameter('column');
        if ($columnName === null) {
            throw new InvalidArgumentException(sprintf(
                'You must define a \'column\' parameter for the \'dpi_sluggable\' behavior in the \'%s\' table',
                $table->getName()
            ));
        }
        if(!$table->containsColumn($this->getParameter('slug_column')))
        {
            $table->addColumn(array(
                'name' => $this->getParameter('slug_column'),
                'type' => 'VARCHAR',
                'size' => 255
            ));
            // add a unique to column
            $unique = new Unique($this->getColumnForParameter('slug_column'));
            $unique->setName($table->getCommonName() . '_slug');
            $unique->addColumn($table->getColumn($this->getParameter('slug_column')));
            if ($this->getParameter('is_i18n') == 'true')
            {
              $unique->addColumn($table->getColumn($this->getParameter('i18n_column')));
            }
            $table->addUnique($unique);
        }
    }

    /**
     * Get the getter of the column of the behavior
     *
     * @return string The related getter, e.g. 'getSlug'
     */
    protected function getColumnGetter()
    {
        return 'get' . $this->getColumnForParameter('slug_column')->getPhpName();
    }

    /**
     * Get the setter of the column of the behavior
     *
     * @return string The related setter, e.g. 'setSlug'
     */
    protected function getColumnSetter()
    {
        return 'set' . $this->getColumnForParameter('slug_column')->getPhpName();
    }


    /**
     * Add code in ObjectBuilder::preSave
     *
     * @return string The code to put at the hook
     */
    public function preSave($builder)
    {
        $const = $builder->getColumnConstant($this->getColumnForParameter('slug_column'));
        $script = "
if (\$this->isColumnModified($const) && \$this->{$this->getColumnGetter()}()) {
  \$this->{$this->getColumnSetter()}(\$this->makeSlugUnique(\$this->{$this->getColumnGetter()}()));";
        if ($this->getParameter('permanent') == 'true') {
            $script .= "
} elseif (!\$this->{$this->getColumnGetter()}()) {
  \$this->{$this->getColumnSetter()}(\$this->createSlug());
}";
        } else {
            $script .= "
} else {
  \$this->{$this->getColumnSetter()}(\$this->createSlug());
}";
        }

        return $script;
    }

    public function objectMethods($builder)
    {
        $this->builder = $builder;
        
        $script = '';
        if ($this->getParameter('slug_column') != 'slug')
        {
            $this->addSlugSetter($script);
            $this->addSlugGetter($script);
        }
        $this->addCreateSlug($script);
        $this->addCreateRawSlug($script);
        $this->addLimitSlugSize($script);
        $this->addMakeSlugUnique($script);

        return $script;
    }

    protected function addSlugSetter(&$script)
    {
        $script .= "
/**
* Wrap the setter for slug value
*
* @param   string
* @return  " . $this->getTable()->getPhpName() . "
*/
public function setSlug(\$v)
{
        return \$this->" . $this->getColumnSetter() . "(\$v);
}
";
    }

    protected function addSlugGetter(&$script)
    {
        $script .= "
/**
* Wrap the getter for slug value
*
* @return  string
*/
public function getSlug()
{
        return \$this->" . $this->getColumnGetter() . "();
}
";
    }

    protected function addCreateSlug(&$script)
    {
        $script .= "
/**
 * Create a unique slug based on the object
 *
 * @return string The object slug
 */
protected function createSlug()
{
        \$slug = \$this->createRawSlug();
        \$slug = \$this->limitSlugSize(\$slug);
        \$slug = \$this->makeSlugUnique(\$slug);

        return \$slug;
}
";
    }

    protected function addCreateRawSlug(&$script)
    {
//        $pattern = $this->getParameter('slug_pattern');
        $script .= "
/**
 * Create the slug from the appropriate columns
 *
 * @return string
 */
protected function createRawSlug()
{
        return dpiUtilities::slugify(\$this->get" . $this->getColumnForParameter('column')->getPhpName() . "(), " . $this->getParameter('keep_letter_case') . ");
}
";
        return $script;
    }

    public function addLimitSlugSize(&$script)
    {
        $size = $this->getColumnForParameter('slug_column')->getSize();
        $script .= "

/**
 * Make sure the slug is short enough to accomodate the column size
 *
 * @param string \$slug     the slug to check
 *
 * @return string           the truncated slug
 */
protected static function limitSlugSize(\$slug, \$incrementReservedSpace = 3)
{
  // check length, as suffix could put it over maximum
  if (strlen(\$slug) > ($size - \$incrementReservedSpace)) {
    \$slug = substr(\$slug, 0, $size - \$incrementReservedSpace);
  }
  return \$slug;
}
";
    }

    public function addMakeSlugUnique(&$script)
    {
        $script .= "

/**
 * Get the slug, ensuring its uniqueness
 *
 * @param string \$slug     the slug to check
 * @param string \$separator the separator used by slug
 * @return string           the unique slug
 */
protected function makeSlugUnique(\$slug, \$separator = '" . $this->getParameter('separator') ."', \$increment = 0)
{
  \$slug2 = empty(\$increment) ? \$slug : \$slug . \$separator . \$increment;
  \$slugAlreadyExists = " . $this->builder->getStubQueryBuilder()->getClassname() . "::create()
    ->filterBySlug(\$slug2)";
        if ($this->getParameter('is_i18n') == 'true') {
          $script .= "
    ->filterBy" . $this->getColumnForParameter('i18n_column')->getPhpName() . "(\$this->get" . $this->getColumnForParameter('i18n_column')->getPhpName() . "())";
        }
        $script .= "
    ->prune(\$this)";
        // watch out: some of the columns may be hidden by the soft_delete behavior
        if ($this->table->hasBehavior('soft_delete')) {
          $script .= "
    ->includeDeleted()";
    }
        $script .= "
    ->count();
  if (\$slugAlreadyExists)
  {
    return \$this->makeSlugUnique(\$slug, \$separator, ++\$increment);
  }
  else
  {
    return \$slug2;
  }
}
";
    }

    public function queryMethods($builder)
    {
        $this->builder = $builder;
        $script = '';
        if ($this->getParameter('slug_column') != 'slug') {
            $this->addFilterBySlug($script);
        }
        $this->addFindOneBySlug($script);

        return $script;
    }

    protected function addFilterBySlug(&$script)
    {
        $script .= "
/**
 * Filter the query on the slug column
 * 
 * @param     string \$slug The value to use as filter.
 *
 * @return    " . $this->builder->getStubQueryBuilder()->getClassname() . " The current query, for fluid interface
 */
public function filterBySlug(\$slug)
{
  return \$this->addUsingAlias(" . $this->builder->getColumnConstant($this->getColumnForParameter('slug_column')) . ", \$slug, Criteria::EQUAL);
}
";
    }

    protected function addFindOneBySlug(&$script)
    {
        $script .= "
/**
 * Find one object based on its slug
 * 
 * @param     string \$slug The value to use as filter.
 * @param     PropelPDO \$con The optional connection object
 *
 * @return    " . $this->builder->getStubObjectBuilder()->getClassname() . " the result, formatted by the current formatter
 */
public function findOneBySlug(\$slug, \$con = null)
{
  return \$this->filterBySlug(\$slug)->findOne(\$con);
}
";
    }
}
