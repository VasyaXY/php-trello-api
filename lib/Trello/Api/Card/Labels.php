<?php

namespace Trello\Api\Card;

use Trello\Api\AbstractApi;
use Trello\Exception\InvalidArgumentException;
use Trello\Api\Label;

/**
 * Trello Card Labels API
 * @link https://trello.com/docs/api/card
 *
 * Fully implemented.
 */
class Labels extends AbstractApi
{
    protected $path = 'cards/#id#';

    /**
     * Set a given card's labels
     * @link https://trello.com/docs/api/card/#put-1-cards-card-id-or-shortlink-labels
     *
     * @param string $id the card's id or short link
     * @param array $labels the labels
     *
     * @return array card info
     *
     * @throws InvalidArgumentException If a label does not exist
     */
    public function set($id, array $labels)
    {
        foreach ($labels as $label) {
            if (!in_array($label, Label::$colors)) {
                throw new InvalidArgumentException(sprintf('Label "%s" does not exist.', $label));
            }
        }

        $labels = implode(',', $labels);

        return $this->put($this->getPath($id).'/labels', ['value' => $labels]);
    }

    /**
     * Create a given card's label
     * @link https://trello.com/docs/api/card/#put-1-cards-card-id-or-shortlink-labels
     *
     * @param string $id the card's id or short link
     * @param array $params the label
     *
     * @return array card info
     *
     * @throws InvalidArgumentException If a label does not exist
     */
    public function create($id, array $params = [])
    {		
		if ((isset($params['color']) && !empty($params['color'])) && !in_array($params['color'], Label::$colors)) {
			throw new InvalidArgumentException(sprintf('Wrong label color "%s". Allowed: '.implode(', ', Label::$colors), $label));
		}

        $labels = implode(',', $labels);

        return $this->post($this->getPath($id).'/labels', $params);
    }
	
    /**
     * Add a given card's label
     * @link https://developer.atlassian.com/cloud/trello/rest/api-group-cards/#api-cards-id-idlabels-post
     *
     * @param string $id the card's id or short link
     * @param string $label the label to add
     *
     * @return array card info
     *
     * @throws InvalidArgumentException If a label does not exist
     */
    public function attach($id, $labelId)
    {
        return $this->post($this->getPath($id).'/idLabels', ['value' => $labelId]);
    }

    /**
     * Remove a given label from a given card
     * @link https://trello.com/docs/api/card/#delete-1-cards-card-id-or-shortlink-labels-color
     *
     * @param string $id the card's id or short link
     * @param string $label the label to remove
     *
     * @return array card info
     *
     * @throws InvalidArgumentException If a label does not exist
     */
    public function remove($id, $label)
    {
        if (!in_array($label, Label::$colors)) {
            throw new InvalidArgumentException(sprintf('Label "%s" does not exist.', $label));
        }

        return $this->delete($this->getPath($id) .'/labels/' . rawurlencode($label));
    }

    /**
     * Remove a given label from a given card
     * @link https://developer.atlassian.com/cloud/trello/rest/api-group-cards/#api-cards-id-idlabels-idlabel-delete
     *
     * @param string $id the card's id or short link
     * @param string $labelId the label to remove
     *
     * @return array card info
     *
     * @throws InvalidArgumentException If a label does not exist
     */
    public function detach($id, $labelId)
    {
        return $this->delete($this->getPath($id) . '/idLabels/' . rawurlencode($labelId));
    }
}
