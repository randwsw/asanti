<?php

/**
 *	one place for configuration.
 *
 *	@copyright  Copyright (c) 2011-2012, PayU
 *	@license    http://opensource.org/licenses/GPL-3.0  Open Software License (GPL 3.0)		
 */

include_once("sdk/openpayu.php");

// Used settings belongs to sandbox test merchant account, ready to start.
// But its better to create own sandbox account and use it for testing.

OpenPayU_Configuration::setEnvironment('sandbox');
OpenPayU_Configuration::setMerchantPosId('41464');
OpenPayU_Configuration::setPosAuthKey('5fJobNi');
OpenPayU_Configuration::setClientId('41464');
OpenPayU_Configuration::setClientSecret('bacbf7a3ec57952ed39ae0db6654f53a');
OpenPayU_Configuration::setSignatureKey('96151e0676b8c6f167c91ed0ad5d7b61');