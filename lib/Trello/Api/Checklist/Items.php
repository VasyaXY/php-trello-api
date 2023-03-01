<?php

namespace Trello\Api\Checklist;

use Trello\Api\AbstractApi;

/**
 * Trello Checklist Items API
 * @link https://trello.com/docs/api/checklist
 *
 * Fully implemented.
 */
class Items extends AbstractApi
{
    protected $path = 'checklists/#id#/checkItems';

    public static $fields = [
        'name',
        'nameData',
        'type',
        'pos',
        'state',
    ];

    /**
     * Get items related to a given checklist
     * @link https://trello.com/docs/api/checklist/#get-1-checklists-idchecklist-checkitems
     *
     * @param string $id the card's id or short link
     * @param array $params optional parameters
     *
     * @return array
     */
    public function all($id, array $params = [])
    {
        return $this->get($this->getPath($id), $params);
    }

    /**
     * Get an item in the given checklist
     * @link https://developer.atlassian.com/cloud/trello/rest/api-group-checklists/#api-checklists-id-checkitems-idcheckitem-get
     *
     * @param string $id the id of a given checklist
     * @param string $itemId the card's id or short link
     * @param array $params optional parameters
     *
     * @return array
     */
    public function getItem($id, $itemId, array $params = [])
    {
        return $this->get($this->getPath($id).'/'.$itemId, $params);
    }

    /**
     * Create an item in the given checklist
     * @link https://trello.com/docs/api/checklist/#post-1-checklists-idchecklist-checkitems
     *
     * @param string $id Id of the checklist
     * @param string $name Name of the item
     * @param bool $checked Check status
     * @param array $data optional attributes
     *
     * @return array
     */
    public function create($id, $name, $checked = false, array $data = [])
    {
        $data['checked'] = $checked;
        $data['name'] = $name;

        return $this->post($this->getPath($id), $data);
    }

    /**
     * Update an item in the given checklist
     *
     * FIXME
     * There is no put method on checklist items, so this is
     * a dirty workaround which works by deleting the item
     * and recreating it.
     *
     * @param string $id Id of the checklist
     * @param string $itemId the id of the item to update
     * @param array $data check item data
     *
     * @return array
     */
    public function update($id, $itemId, array $data)
    {	
		$item = $this->getItem($id, $itemId);
		if(!isset($data['pos'])) {
			$data['pos'] = $item['pos'];
		}
		if(!isset($data['name']) || $data['name'] == '') {
			$data['name'] = $item['name'];
		}
		if(!isset($data['state'])) {
			$data['state'] = $item['state'] == 'complete' ? true : false;
		}

        $this->remove($id, $itemId);

        return $this->create($id, $data['name'], $data['state'], $data);
    }

    /**
     * Remove an item from checklist
     * @link https://trello.com/docs/api/checklist/#delete-1-checklists-idchecklist-checkitems-idcheckitem
     *
     * @param string $id the id of the checklist the item should be removed from
     * @param string $itemId the id of the item to delete
     *
     * @return array card info
     */
    public function remove($id, $itemId)
    {
        return $this->delete($this->getPath($id) . '/' . rawurlencode($itemId));
    }
}
