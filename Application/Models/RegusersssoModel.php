<?php

// EcommerceMyAdmin is a PHP CMS based on MvcMyLibrary
//
// Copyright (C) 2009 - 2026  Antonio Gallo (info@laboratoriolibero.com)
// See COPYRIGHT.txt and LICENSE.
//
// This file is part of EcommerceMyAdmin
//
// EcommerceMyAdmin is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// EcommerceMyAdmin is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with EcommerceMyAdmin.  If not, see <http://www.gnu.org/licenses/>.

if (!defined('EG')) die('Direct access not allowed!');

class RegusersssoModel extends GenericModel
{
	private static $expiredTokensCleanedThisRequest = false;

	public function __construct() {
		$this->_tables='regusers_sso';
		$this->_idFields='id_user_sso';
		
		parent::__construct();

		// Rimuovi i token scaduti una sola volta per richiesta, anche se il model
		// viene istanziato più volte durante la stessa esecuzione.
		if (!self::$expiredTokensCleanedThisRequest)
		{
			self::$expiredTokensCleanedThisRequest = true;
			
			$expiryCutoff = date('Y-m-d H:i:s', time() - 60);
			$this->query(array(
				"DELETE FROM regusers_sso WHERE expires_at < ?",
				array($expiryCutoff),
			));
		}
	}
	
	public function relations() {
        return array(
			'utente' => array("BELONGS_TO", 'RegusersModel', 'id_user',null,"RESTRICT", "Si prega di indicare l'utente"),
        );
    }

	/**
	 * Riscatta un token SSO una sola volta e restituisce l'utente associato.
	 */
	public function redeemSsoToken($token)
	{
		if ((string)v("attiva_sso") !== '1')
			return $this->ssoRedemptionResult('KO', 'SSO non attivo.');

		if ((int)v("sso_durata_token_secondi") <= 0)
			return $this->ssoRedemptionResult('KO', 'Durata del token SSO non valida.');

		if (!is_string($token) || !preg_match('/^[a-f0-9]{64}$/i', $token))
			return $this->ssoRedemptionResult('KO', 'Token SSO non valido.');

		$tokenHash = hash('sha256', $token);
		$this->db->beginTransaction();

		// Blocca la riga per impedire riscatti simultanei dello stesso token.
		$rows = $this->query(array(
			"SELECT id_user_sso, id_user, expires_at, token_used FROM regusers_sso WHERE token_hash = ? LIMIT 1 FOR UPDATE",
			array($tokenHash),
		));

		if (empty($rows[0]['regusers_sso']))
		{
			$this->db->commit();
			return $this->ssoRedemptionResult('KO', 'Token SSO inesistente o scaduto.');
		}

		$ssoToken = $rows[0]['regusers_sso'];
		if ((int)$ssoToken['token_used'] === 1)
		{
			$this->db->commit();
			return $this->ssoRedemptionResult('KO', 'Token SSO già utilizzato.');
		}

		$expiresAt = strtotime($ssoToken['expires_at']);
		if ($expiresAt === false || $expiresAt <= time())
		{
			$this->db->commit();
			return $this->ssoRedemptionResult('KO', 'Token SSO scaduto.');
		}

		$idUser = (int)$ssoToken['id_user'];
		if ($idUser <= 0)
		{
			$this->db->commit();
			return $this->ssoRedemptionResult('KO', 'Utente associato al token SSO non valido.');
		}

		// Verifica che l'account esista e sia attivo secondo la configurazione utenti.
		$regusersModel = new RegusersModel();
		$user = $regusersModel->clear()->where(array(
			'id_user' => $idUser,
		))->record();
		$statusFieldName = Users_CheckAdmin::$statusFieldName;
		if (empty($user) || !isset($user[$statusFieldName]) ||
			(string)$user[$statusFieldName] !== (string)Users_CheckAdmin::$statusFieldActiveValue)
		{
			$this->db->commit();
			return $this->ssoRedemptionResult('KO', 'Utente inesistente o non attivo.');
		}

		$usedAt = date('Y-m-d H:i:s');
		$this->sValues(array(
			'token_used' => 1,
			'used_at' => $usedAt,
		));
		$updated = $this->pUpdate((int)$ssoToken['id_user_sso']);

		if (!$updated)
		{
			$this->db->commit();
			return $this->ssoRedemptionResult('KO', 'Impossibile completare il riscatto del token SSO.');
		}

		$this->db->commit();

		return $this->ssoRedemptionResult('OK', '', $idUser);
	}

	private function ssoRedemptionResult($result, $error, $idUser = 0)
	{
		return array(
			'result' => $result,
			'errore' => $error,
			'id_user' => (int)$idUser,
		);
	}
}
