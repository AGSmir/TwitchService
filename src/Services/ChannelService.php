<?php

namespace App\Services;

use App\DataClass\Channel\Channel;
use App\Repository\Channel\ChannelRepositoryInterface;

class ChannelService
{
    private ChannelRepositoryInterface $channelRepository;

    public function __construct(ChannelRepositoryInterface $channelRepository)
    {
        $this->channelRepository = $channelRepository;
    }

    function addAccount(Channel $channel): void
    {
        $this->channelRepository->save($channel);
    }

    public function findByLogin(string $login): ?Channel
    {
        return $this->channelRepository->findByLogin($login);
    }
}
