<?php

namespace Trello;

use Trello\Model\Action;
use Trello\Model\BoardInterface;
use Trello\Model\CardInterface;
use Trello\Model\CardListInterface;
use Trello\Model\CheckListInterface;
use Trello\Model\MemberInterface;
use Trello\Model\TokenInterface;
use Trello\Model\WebhookInterface;

class Manager
{
    /**
     * Constructor.
     *
     * @param ClientInterface $client
     */
    public function __construct(protected ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * Get organization by id or create a new one
     *
     * @param string $id the organization's id
     *
     * @return Model\OrganizationInterface
     */
    public function getOrganization(string $id = null)
    {
        return new Model\Organization($this->client, $id);
    }

    /**
     * Get board by id or create a new one
     *
     * @param string $id the board's id
     *
     * @return Model\BoardInterface
     */
    public function getBoard(string $id = null): BoardInterface
    {
        return new Model\Board($this->client, $id);
    }

    /**
     * Get cardlist by id or create a new one
     *
     * @param string $id the cardlist's id
     *
     * @return Model\CardListInterface
     */
    public function getList(string $id = null): CardListInterface
    {
        return new Model\CardList($this->client, $id);
    }

    /**
     * Get card by id or create a new one
     *
     * @param string $id the card's id
     *
     * @return Model\CardInterface
     */
    public function getCard(string $id = null): CardInterface
    {
        return new Model\Card($this->client, $id);
    }

    /**
     * Get checklist by id or create a new one
     *
     * @param string $id the checklist's id
     *
     * @return Model\CheckListInterface
     */
    public function getChecklist(string $id = null): CheckListInterface
    {
        return new Model\CheckList($this->client, $id);
    }

    /**
     * Get member by id or create a new one
     *
     * @param string $id the member's id
     *
     * @return Model\MemberInterface
     */
    public function getMember(string $id = null): MemberInterface
    {
        return new Model\Member($this->client, $id);
    }

    /**
     * Get action by id
     *
     * @param string $id the action's id
     *
     * @return Model\ActionInterface
     */
    public function getAction(string $id): Action
    {
        return new Model\Action($this->client, $id);
    }

    /**
     * Get token by id
     *
     * @param string $id the token's id
     *
     * @return Model\TokenInterface
     */
    public function getToken(string $id): TokenInterface
    {
        return new Model\Token($this->client, $id);
    }

    /**
     * Get webhook by id
     *
     * @param string $id the webhook's id
     *
     * @return Model\WebhookInterface
     */
    public function getWebhook(string $id): WebhookInterface
    {
        return new Model\Webhook($this->client, $id);
    }
}
