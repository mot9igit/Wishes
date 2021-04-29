<?php

class WishesUpdateProcessor extends modObjectUpdateProcessor
{
    public $objectType = 'WishesItems';
    public $classKey = 'WishesItems';
    public $languageTopics = ['wishes'];
    //public $permission = 'save';


    /**
     * We doing special check of permission
     * because of our objects is not an instances of modAccessibleObject
     *
     * @return bool|string
     */
    public function beforeSave()
    {
        if (!$this->checkPermissions()) {
            return $this->modx->lexicon('access_denied');
        }

        return true;
    }


    /**
     * @return bool
     */
    public function beforeSet()
    {
        $id = (int)$this->getProperty('id');
        $name = trim($this->getProperty('name'));
        if (empty($id)) {
            return $this->modx->lexicon('wishes_item_err_ns');
        }

        if (empty($name)) {
            $this->modx->error->addField('name', $this->modx->lexicon('wishes_item_err_name'));
        } elseif ($this->modx->getCount($this->classKey, ['name' => $name, 'id:!=' => $id])) {
            $this->modx->error->addField('name', $this->modx->lexicon('wishes_item_err_ae'));
        }

		$this->setFieldDefaults();

        return parent::beforeSet();
    }

	/**
	 * Set defaults for the fields if values are not passed
	 * @return mixed
	 */
	public function setFieldDefaults()
	{
		$scriptProperties = $this->getProperties();
		if(empty($scriptProperties['editedon'])){
			$scriptProperties['editedon'] = strftime('%Y-%m-%d %H:%M:%S');
		}

		if(empty($scriptProperties['editedby'])){
			$scriptProperties['editedby'] = $this->modx->user->get('id');
		}
		$this->setProperties($scriptProperties);
		return true;
	}
}

return 'WishesUpdateProcessor';
