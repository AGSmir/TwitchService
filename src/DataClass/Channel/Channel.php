<?php
declare(strict_types=1);

namespace App\DataClass\Channel;

use DomainException;

final readonly class Channel
{
    private function __construct(
        public string   $twitchId,
        public string   $login,
        public string   $displayName,
        public ?int     $id = null,
        public bool     $isPartner = false,
        public bool     $isAffiliate = false,
    ) {}

    public static function fromArray(array $data): self
    {
        self::validate($data);
        return new self(
            twitchId: $data['twitch_id'],
            login: $data['login'],
            displayName: $data['display_name'],
            id: (int)$data['id'],
            isPartner: $data['is_partner'],
            isAffiliate: $data['is_affiliate']
        );
    }

    private static function validate(array $data): void
    {
        if (empty($data['twitch_id'])) {
            throw new DomainException('Invalid Twitch ID');
        }

        if (empty($data['display_name'])) {
            throw new DomainException('Invalid Display name');
        }
    }

    public function isPartner(): bool
    {
        return $this->isPartner;
    }

    public function isAffiliate(): bool
    {
        return $this->isAffiliate;
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
