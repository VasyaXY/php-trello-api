<?php

namespace Trello\Model;

interface CheckListInterface extends ObjectInterface
{
    /**
     * Set name
     *
     * @param string $name
     *
     * @return CheckListInterface
     */
    public function setName($name);

    /**
     * Get name
     *
     * @return string
     */
    public function getName();

    /**
     * Set boardId
     *
     * @param string $boardId
     *
     * @return CheckListInterface
     */
    public function setBoardId($boardId);

    /**
     * Get boardId
     *
     * @return string
     */
    public function getBoardId();

    /**
     * Set board
     *
     * @param BoardInterface $board
     *
     * @return CheckListInterface
     */
    public function setBoard(BoardInterface $board);

    /**
     * Get board
     *
     * @return BoardInterface
     */
    public function getBoard();

    /**
     * Set card id
     *
     * @param string $cardId
     *
     * @return CheckListInterface
     */
    public function setCardId($cardId);

    /**
     * Get card id
     *
     * @return string
     */
    public function getCardId();

    /**
     * Set card
     *
     * @param CardInterface $card [description]
     *
     * @return CheckListInterface
     */
    public function setCard(CardInterface $card);

    /**
     * Get card
     *
     * @return CardInterface
     */
    public function getCard();

    /**
     * Set position
     *
     * @param string $pos
     *
     * @return CheckListInterface
     */
    public function setPosition($pos);

    /**
     * Get position
     *
     * @return string
     */
    public function getPosition();

    /**
     * Get checklist items
     *
     * @return array an array of check item arrays. each check item array has the following keys:
     *               - id : the identifier of the item
     *               - name : the name (label) o fthe item
     *               - state : 'complete' or 'incomplete'
     *               - pos : the position of the item
     */
    public function getItems();

    /**
     * Set an item
     *
     * @param string $name name of the item
     * @param bool $checked whether it should be marked as completed or not
     * @param integer $position position on the list
     *
     * @return CheckListInterface
     */
    public function setItem($name, $checked = null, $position = null);

    /**
     * Find out whether the item with the given name is checked
     *
     * @param string $name the item's name
     *
     * @return boolean
     */
    public function isItemChecked($name);
}
