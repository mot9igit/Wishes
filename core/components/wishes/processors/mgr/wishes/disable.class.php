<?php

class WishesDisableProcessor extends modObjectProcessor
{
    public $objectType = 'WishesItems';
    public $classKey = 'WishesItems';
    public $languageTopics = ['wishes'];
    //public $permission = 'save';


    /**
     * @return array|string
     */
    public function process()
    {
        if (!$this->checkPermissions()) {
            return $this->failure($this->modx->lexicon('access_denied'));
        }

        $ids = $this->modx->fromJSON($this->getProperty('ids'));
        if (empty($ids)) {
            return $this->failure($this->modx->lexicon('wishes_item_err_ns'));
        }

        foreach ($ids as $id) {
            /** @var WishesItem $object */
            if (!$object = $this->modx->getObject($this->classKey, $id)) {
                return $this->failure($this->modx->lexicon('wishes_item_err_nf'));
            }

            $object->set('active', false);
			$object->set('endon', strftime('%Y-%m-%d %H:%M:%S'));
			$object->set('editedon', strftime('%Y-%m-%d %H:%M:%S'));
			$object->set('editedby', $this->modx->user->get('id'));
            $object->save();
        }

        return $this->success();
    }
}

return 'WishesDisableProcessor';
