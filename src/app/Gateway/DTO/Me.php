<?php

namespace App\Gateway\DTO;

use Symfony\Component\Serializer\Annotation\SerializedName;

final readonly class Me
{
    public function __construct(
        #[SerializedName('id')]
        public int $gwId,
        #[SerializedName('crm_id')]
        public ?int $crmId,
        #[SerializedName('nick_name')]
        public ?string $nickName,
        #[SerializedName('first_name')]
        public ?string $firstName,
        #[SerializedName('last_name')]
        public ?string $lastName,
        #[SerializedName('middle_name')]
        public ?string $middleName,
        public string $email,
        #[SerializedName('email_alt')]
        public ?string $emailAlt,
        public string $phone,
        #[SerializedName('phone_mobile')]
        public ?string $phoneMobile,
    ) {
    }
}
