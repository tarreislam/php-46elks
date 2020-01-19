<?php


namespace Tarre\Php46Elks\Clients\SMS\Traits;

use Tarre\Php46Elks\Exceptions\InvalidSenderIdException;

trait SenderTrait
{
    protected $senderId = null;

    /**
     * @param string $senderId
     * @return $this
     * @throws InvalidSenderIdException
     */
    public function from(string $senderId): self
    {
        if (!preg_match('/[a-z]+[a-z0-9]+/i', $senderId)) {
            throw new InvalidSenderIdException($senderId);
        }

        $this->senderId = $senderId;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getFrom()
    {
        return $this->senderId;
    }


}
