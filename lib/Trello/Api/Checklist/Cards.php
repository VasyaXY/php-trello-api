<?php

namespace Trello\Api\Checklist;

use Trello\Api\AbstractApi;

/**
 * Trello Checklist Cards API
 * @link https://trello.com/docs/api/checklist
 *
 * Fully implemented.
 */
class Cards extends AbstractApi
{
    protected string $path = 'checklists/#id#/cards';

    /**
     * Get cards related to a given checklist
     * @link https://trello.com/docs/api/checklist/#get-1-checklists-idchecklist-cards
     *
     * @param string $id the checklist's id
     * @param array $params optional parameters
     *
     * @return array
     */
    public function all(string $id, array $params = []): array
    {
        return $this->get($this->getPath($id), $params);
    }

    /**
     * Filter cards related to a given checklist
     * @link https://trello.com/docs/api/checklist/#get-1-checklists-idchecklist-cards-filter
     *
     * @param string $id the checklist's id
     * @param array $filter one of 'none', 'open', 'closed', 'all'
     *
     * @return array
     */
    public function filter(string $id, string|array $filter = 'all'): array
    {
        $allowed = ['none', 'open', 'closed', 'all'];
        $filters = $this->validateAllowedParameters($allowed, $filter, 'filter');

        return $this->get($this->getPath($id) . '/' . implode(',', $filters));
    }
}
