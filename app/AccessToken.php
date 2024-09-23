<?php

namespace App;

use Laravel\Passport\Bridge\AccessToken as PassportAccessToken;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Signer\Rsa\Sha256;
use League\OAuth2\Server\CryptKey;

class AccessToken extends PassportAccessToken {
    private $privateKey;

    public function __toString() {
        return (string) $this->convertToJWT($this->privateKey);
    }

    public function setPrivateKey(CryptKey $privateKey) {
        $this->privateKey = $privateKey;
    }

    public function convertToJWT(CryptKey $privateKey) {
        $builder = new Builder();
        $builder->permittedFor($this->getClient()->getIdentifier())
            ->identifiedBy($this->getIdentifier(), true)
            ->issuedAt(time())
            ->canOnlyBeUsedAfter(time())
            ->expiresAt($this->getExpiryDateTime()->getTimestamp())
            ->relatedTo($this->getUserIdentifier())
            ->withClaim('scopes', $this->getScopes())
            ->withClaim('roles', $this->getRoles());

        return $builder
            ->getToken(new Sha256(), new Key($privateKey->getKeyPath(), $privateKey->getPassPhrase()));
    }

    public function getRoles() {
        $roles = [];
        $isWizard = Driver::whereHas('user')->whereUserId($this->getUserIdentifier())->notActive()->where('step', '<', 5)->exists();
        if($isWizard) {
            $roles[] = 'wizard';
        }

        $isDriver = Driver::whereHas('user')->whereUserId($this->getUserIdentifier())->exists();
        if($isDriver) {
            $roles[] = 'driver';
        }

        $isPartner = Partner::whereHas('user')->whereUserId($this->getUserIdentifier())->exists();
        if($isPartner) {
            $roles[] = 'partner';
        }
        return $roles;
    }

}
