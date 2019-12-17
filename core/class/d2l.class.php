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
	

    /*     * ***********************Methode static*************************** */
  
  
  public static function getAPIKey() {
		log::add('d2l', 'info', 'getAPIKey');
		$login = config::byKey('consospy_login', 'd2l', 0);
		$password = config::byKey('consospy_password', 'd2l', 0);
		if ($login == 0) {
		}
		if ($password == 0) {
		}
    
		log::add('d2l', 'debug', 'getAPIKey login ' . $login);
    
		log::add('d2l', 'debug', 'getAPIKey password ' . $password);
    	$data = array(
            'login' => $login,
            'password' => $password
        );
    	$payload = json_encode($data);
    	$ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://consospyapi.sicame.io/api/D2L/Security/GetAPIKey');
        curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($payload))
        );
        $response = curl_exec($ch);
		curl_close($ch);
		log::add('d2l', 'debug', 'Retour getAPIKey ' . $response);
		log::add('d2l', 'debug', 'Retour getAPIKey ' . print_r($response));
		$result = json_decode($response);
		log::add('d2l', 'debug', 'Retour getAPIKey ' . $result->apiKey);
		config::save('apiKey', $result->apiKey, 'd2l'); 
	}

	public static function getD2Ls() {
		$apiKey = config::byKey('apiKey', 'd2l', 0);
		if ($apiKey == 0) {
		}
		//$request = new com_http('https://consospyapi.sicame.io/api/D2L/D2Ls');
      	$ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://consospyapi.sicame.io/api/D2L/D2Ls');
        curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('APIKey: '.$apiKey));
        $result = curl_exec($ch);
		curl_close($ch);
		log::add('d2l', 'debug', 'getD2Ls APIKey ' . $apiKey);
		//$request->setHeader(array("APIKey"=>$apiKey));
		//$result = $request->exec();
      	/*if($result == 'Request is HTTP, Basic Authentication will not respond.'){
          throw new Exception(__('Erreur : ', __FILE__) . $result);
        }*/
        $tab = json_decode($result);
		log::add('d2l', 'debug', 'Retour getD2Ls ' . print_r($tab));
		foreach($tab as $value) {
			log::add('d2l', 'debug', 'Retour getD2Ls  valeur ' . $value->idModule);
			$d2l = self::byLogicalId($value->idModule, 'd2l');
			if (!is_object($d2l)) {
				log::add('d2l', 'info', 'Equipement n existe pas, création ' . $value->idModule);
				$d2l = new d2l();
				$d2l->setEqType_name('d2l');
				$d2l->setLogicalId($value->idModule);
				$d2l->setName($value->idModule);
				$d2l->setIsEnable(true);
				$d2l->save();
				$d2l->setConfiguration('d2l', $value->idModule);
				$d2l->save();
				//log::add('d2l', 'info',   print_r($d2l,true));
			}
          self::getLastIndexes($value->idModule);
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
		$ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://consospyapi.sicame.io/api/D2L/D2Ls/'.$id.'/LastIndexes');
        curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('APIKey: '.$apiKey));
        $result = curl_exec($ch);
		curl_close($ch);
		log::add('d2l', 'debug', 'Retour getLastIndexes ' . print_r($result,true));
		$d2l = self::byLogicalId($id, 'd2l');
        $tab = json_decode($result);
		$timestamp = date("Y-m-d H:i:s", strtotime($tab->horloge));
		foreach($tab as $key => $value ) {
			log::add('d2l', 'debug', 'Retour getLastIndexes ' . $key . ' valeur ' . $value);
			$d2lCmd = d2lCmd::byEqLogicIdAndLogicalId($d2l->getId(),$key);
			if (!is_object($d2lCmd) && $value) {
				$d2lCmd = new d2lCmd();
				$d2lCmd->setEqLogic_id($d2l->getId());
				$d2lCmd->setEqType('d2l');
				$d2lCmd->setLogicalId($key);
				$d2lCmd->setName( $key );
				$d2lCmd->setType('info');
				if($key == 'horloge'){
					$d2lCmd->setSubType('string');
				}else{	
					$d2lCmd->setSubType('numeric');
				}
				$d2lCmd->save();
			}
			$d2l->checkAndUpdateCmd($key, $value, $timestamp);
		}
	}
	

      public static function cron() {
		
		$eqLogics = self::byType('d2l');
	    foreach ($eqLogics as $eqLogic) {
            try {
				self::getLastIndexes($eqLogic->getId());
			}catch (Exception $e) {
			self::getAPIKey();
			$result = self::getLastIndexes($eqLogic->getId());
			}
        }
      }
	  
	  public static function synchronize() {
		try {
			$result = self::getD2Ls();
		} catch (Exception $e) {
			self::getAPIKey();
			$result = self::getD2Ls();
		}
      }
    



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