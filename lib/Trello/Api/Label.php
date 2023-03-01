<?php

namespace Trello\Api;

use Trello\Exception\InvalidArgumentException;

/**
 * Trello Label API
 * @link https://developer.atlassian.com/cloud/trello/rest/api-group-labels/
 */
class Label extends AbstractApi
{
    /**
     * Base path of labels api
     * @var string
     */
    protected $path = 'labels';

    /**
     * Label fields
     * @var array
     */
    public static $fields = [
        'name',
        'color',
        'idBoard'
    ];

    /**
     * Label colors
     * @var array
     */
    public static $colors = [
		'yellow', 
		'purple', 
		'blue', 
		'red', 
		'green', 
		'orange', 
		'black', 
		'sky', 
		'pink', 
		'lime'
    ];

    /**
     * Find a label by id
     * @link https://developer.atlassian.com/cloud/trello/rest/api-group-labels/#api-labels-id-get
     *
     * @param string $id the label's id or short link
     * @param array $params optional attributes
     *
     * @return array label info
     */
    public function show($id, array $params = [])
    {
        return $this->get($this->getPath() . '/' . rawurlencode($id), $params);
    }

    /**
     * Create a label
     * @link https://developer.atlassian.com/cloud/trello/rest/api-group-labels/#api-labels-post
     *
     * @param array $params optional attributes
     *
     * @return array label info
     */
    public function create(array $params = [])
    {
        $this->validateRequiredParameters(['idBoard', 'name'], $params);
		
		if ((isset($params['color']) && !empty($params['color'])) && !in_array($params['color'], Label::$colors)) {
			throw new InvalidArgumentException(sprintf('Wrong label color "%s". Allowed: '.implode(', ', Label::$colors), $label));
		}

        return $this->post($this->getPath(), $params);
    }

    /**
     * Update a label
     * @link https://developer.atlassian.com/cloud/trello/rest/api-group-labels/#api-labels-id-put
     *
     * @param string $id the label's id or short link
     * @param array $params label attributes to update
     *
     * @return array card info
     */
    public function update($id, array $params = [])
    {
		return $this->put($this->getPath() . '/' . rawurlencode($id), $params);
    }

    /**
     * Set a given label's color
     * @link https://developer.atlassian.com/cloud/trello/rest/api-group-labels/#api-labels-id-field-put
     *
     * @param string $id the label's id or short link
     * @param string $color the label's id
     *
     * @return array label info
     */
    public function setColor($id, $color)
    {
        $this->validateAllowedParameters(Label::$colors, $color, 'color');
        return $this->put($this->getPath() . '/' . rawurlencode($id) . '/color', ['value' => $color]);
    }

    /**
     * Set a given label's name
     * @link https://developer.atlassian.com/cloud/trello/rest/api-group-labels/#api-labels-id-field-put
     *
     * @param string $id the label's id or short link
     * @param string $name the label's id
     *
     * @return array label info
     */
    public function setName($id, $name)
    {
        return $this->put($this->getPath() . '/' . rawurlencode($id) . '/name', ['value' => $name]);
    }
}
