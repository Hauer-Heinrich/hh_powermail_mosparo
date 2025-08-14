<?php
namespace HauerHeinrich\HhPowermailMosparo\Domain\Model;

/**
 * Class Field
 */
class Field extends \In2code\Powermail\Domain\Model\Field {

    protected string $mosparo_host = '';
    protected string $mosparo_uuid = '';
    protected string $mosparo_publickey = '';
    protected string $mosparo_privatekey = '';
    protected string $mosparo_options = '';

    public function getMosparoHost(): string {
        return $this->mosparo_host;
    }

    public function setMosparoHost(string $mosparo_host): void {
        $this->mosparo_host = $mosparo_host;
    }

    public function getMosparoUuid(): string {
        return $this->mosparo_uuid;
    }

    public function setMosparoUuid(string $mosparo_uuid): void {
        $this->mosparo_uuid = $mosparo_uuid;
    }

    public function getMosparoPublickey(): string {
        return $this->mosparo_publickey;
    }

    public function setMosparoPublickey(string $mosparo_publickey): void {
        $this->mosparo_publickey = $mosparo_publickey;
    }

    public function getMosparoPrivatekey(): string {
        return $this->mosparo_privatekey;
    }

    public function setMosparoPrivatekey(string $mosparo_privatekey): void {
        $this->mosparo_privatekey = $mosparo_privatekey;
    }

    public function getMosparoOptions(): string {
        return $this->mosparo_options;
    }

    public function setMosparoOptions(string $mosparo_options): void {
        $this->mosparo_options = $mosparo_options;
    }
}
