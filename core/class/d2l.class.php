<?php

/* This file is part of Jeedom.
 *
 * Jeedom is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Jeedom is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
 */

/* * ***************************Includes********************************* */
require_once __DIR__  . '/../../../../core/php/core.inc.php';

class d2l extends eqLogic {
    /*     * *************************Attributs****************************** */

	public static function getAPIKey() {
		$login = config::byKey('login', 'd2l', 0);
		$password = config::byKey('password', 'd2l', 0);
		if ($login == 0) {
		}
		if ($password == 0) {
		}
		$request = new com_http('https://consospyapi.sicame.io/api/D2L/Security/GetAPIKey');
		$request->setPost(array("login"=>$login, "password"=>$password);
		$result = json_decode($request->exec());
		config::save('apiKey', $result->apiKey, 'd2l'); 
	}

	public static function getD2Ls() {
		$apiKey = config::byKey('apiKey', 'd2l', 0);
		if ($apiKey == 0) {
		}
		$request = new com_http('https://consospyapi.sicame.io/api/D2L/D2Ls');
		$request->setHeader(array("apiKey"=>$apiKey);
		$result = json_decode($request->exec());
		log::add('d2l', 'debug', 'Retour getD2Ls ' . print_r($result,true));
		foreach($result as $key => $value ) {
			log::add('d2l', 'debug', 'Retour getD2Ls ' . $key . ' valeur ' . $value);
			$d2l = self::byLogicalId($value, 'd2l');
			if (!is_object($d2l)) {
				log::add('d2l', 'info', 'Equipement n existe pas, création ' . $value);
				$d2l = new d2l();
				$d2l->setEqType_name('d2l');
				$d2l->setLogicalId($value);
				$d2l->setName($value);
				$d2l->setIsEnable(true);
				$d2l->save();
				$d2l->setConfiguration('d2l', $value);
				$d2l->save();
				//log::add('d2l', 'info',   print_r($d2l,true));
			}
		}
	}

	/*public static function getTypeContrat($id) {
		$apiKey = config::byKey('apiKey', 'd2l', 0);
		if ($apiKey == 0) {
		}
		$request = new com_http('https://consospyapi.sicame.io/api/D2L/D2Ls/$id/TypeContrat');
		$request->setHeader(array("apiKey"=>$apiKey);
		$result = json_decode($request->exec());
		log::add('d2l', 'debug', 'Retour getTypeContrat ' . print_r($result,true));
		foreach($result as $key => $value ) {
			log::add('d2l', 'debug', 'Retour getTypeContrat ' . $key . ' valeur ' . $value);
			$d2l = self::byLogicalId($value, 'd2l');
			if (!is_object($d2l)) {
				log::add('d2l', 'info', 'Equipement n existe pas, création ' . $value);
				$d2l = new d2l();
				$d2l->setEqType_name('d2l');
				$d2l->setLogicalId($value);
				$d2l->setName($value);
				$d2l->setIsEnable(true);
				$d2l->save();
				$d2l->setConfiguration('d2l', $value);
				$d2l->save();
				//log::add('d2l', 'info',   print_r($d2l,true));
			}
		}
	}*/

	public static function getLastIndexes($id) {
		$apiKey = config::byKey('apiKey', 'd2l', 0);
		if ($apiKey == 0) {
		}
		$request = new com_http('https://consospyapi.sicame.io/api/D2L/D2Ls/$id/LastIndexes');
		$request->setHeader(array("apiKey"=>$apiKey);
		$result = json_decode($request->exec());
		log::add('d2l', 'debug', 'Retour getLastIndexes ' . print_r($result,true));
		$d2l = self::byLogicalId($value, 'd2l');
		$timestamp = date("Y-m-d H:i:s", $location['timestamp'] / 1000);
		foreach($result as $key => $value ) {
			log::add('d2l', 'debug', 'Retour getLastIndexes ' . $key . ' valeur ' . $value);
			$d2lCmd = d2lCmd::byEqLogicIdAndLogicalId($d2l->getId(),$key);
			if (!is_object($d2lCmd)) {
				$d2lCmd = new d2lCmd();
				$d2lCmd->setEqLogic_id($d2l->getId());
				$d2lCmd->setEqType('d2l');
				$d2lCmd->setLogicalId($key);
				$d2lCmd->setName( $key );
				$d2lCmd->setType('info');
				if(is_numeric($value)){
					$d2lCmd->setSubType('numeric');
				}else{	
					$d2lCmd->setSubType('other');
				}
				$d2lCmd->save();
			}
			$d2l->checkAndUpdateCmd($key, $value, $timestamp)
		}
	}

	/*public static function getLastsCurrents($id) {
	}

	public static function getConfigurationKey($id) {
	}*/
	
	
	

    /*     * ***********************Methode static*************************** */

    /*
     * Fonction exécutée automatiquement toutes les minutes par Jeedom
      public static function cron() {

      }
     */


    /*
     * Fonction exécutée automatiquement toutes les heures par Jeedom
      public static function cronHourly() {

      }
     */

    /*
     * Fonction exécutée automatiquement tous les jours par Jeedom
      public static function cronDaily() {

      }
     */



    /*     * *********************Méthodes d'instance************************* */

    public function preInsert() {
        
    }

    public function postInsert() {
        
    }

    public function preSave() {
        
    }

    public function postSave() {
        
    }

    public function preUpdate() {
        
    }

    public function postUpdate() {
        
    }

    public function preRemove() {
        
    }

    public function postRemove() {
        
    }

    /*
     * Non obligatoire mais permet de modifier l'affichage du widget si vous en avez besoin
      public function toHtml($_version = 'dashboard') {

      }
     */

    /*
     * Non obligatoire mais ca permet de déclencher une action après modification de variable de configuration
    public static function postConfig_<Variable>() {
    }
     */

    /*
     * Non obligatoire mais ca permet de déclencher une action avant modification de variable de configuration
    public static function preConfig_<Variable>() {
    }
     */

    /*     * **********************Getteur Setteur*************************** */
}

class d2lCmd extends cmd {
    /*     * *************************Attributs****************************** */


    /*     * ***********************Methode static*************************** */


    /*     * *********************Methode d'instance************************* */

    /*
     * Non obligatoire permet de demander de ne pas supprimer les commandes même si elles ne sont pas dans la nouvelle configuration de l'équipement envoyé en JS
      public function dontRemoveCmd() {
      return true;
      }
     */

    public function execute($_options = array()) {
        
    }

    /*     * **********************Getteur Setteur*************************** */
}


