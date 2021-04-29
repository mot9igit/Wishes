<?php

class WishesCreateProcessor extends modObjectCreateProcessor
{
    public $objectType = 'WishesItems';
    public $classKey = 'WishesItems';
    public $languageTopics = ['wishes'];
    //public $permission = 'create';


    /**
     * @return bool
     */
    public function beforeSet()
    {
        $name = trim($this->getProperty('name'));
        if (empty($name)) {
            $this->modx->error->addField('name', $this->modx->lexicon('wishes_item_err_name'));
        } elseif ($this->modx->getCount($this->classKey, ['name' => $name])) {
            $this->modx->error->addField('name', $this->modx->lexicon('wishes_item_err_ae'));
        }

		$active = trim($this->getProperty('active'));
		if($active){
			$wishes = $this->modx->getCollection("WishesItems");
			foreach($wishes as $wish){
				if($wish->get("active")){
					$wish->set('active', false);
					$wish->set('endon', strftime('%Y-%m-%d %H:%M:%S'));
					$wish->set('editedon', strftime('%Y-%m-%d %H:%M:%S'));
					$wish->set('editedby', $this->modx->user->get('id'));
					$wish->save();
				}
			}
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
		if(empty($scriptProperties['createdon'])){
			$scriptProperties['createdon'] = strftime('%Y-%m-%d %H:%M:%S');
		}

		if(empty($scriptProperties['createdby'])){
			$scriptProperties['createdby'] = $this->modx->user->get('id');
		}
		$this->setProperties($scriptProperties);
		return true;
	}

}

return 'WishesCreateProcessor';