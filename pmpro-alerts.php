<?php

$pmpro_alerts = array(
	__( 'Third Party Support', 'wp-security-audit-log' ) => array(
		__( 'User Meta Alerts', 'wp-security-audit-log' ) => array(

			array(
				8501,
				E_NOTICE,
				__( 'User created a meta field for a user', 'wp-security-audit-log' ),
				__( 'Created a new meta field %MetaKey% with value %MetaValue% for user (%UserID%) %DisplayName%', 'wp-security-audit-log' )
			),
			array(
				8502,
				E_NOTICE,
				__( 'User changed a meta field name for a user', 'wp-security-audit-log' ),
				__( 'Changed a meta field name from %MetaKeyOld% to %MetaKeyNew% for user (%UserID%) %DisplayName%', 'wp-security-audit-log' )
			),
			array(
				8503,
				E_NOTICE,
				__( 'User modified a meta field for a user', 'wp-security-audit-log' ),
				__( 'Modified the value of a meta field %MetaKey% from %MetaValueOld% to %MetaValueNew% for user (%UserID%) %DisplayName%', 'wp-security-audit-log' )
			),
			array(
				8504,
				E_WARNING,
				__( 'User deleted a meta field for a user', 'wp-security-audit-log' ),
				__( 'Deleted a meta field %MetaKey% with id %MetaID% for user (%UserID%) %DisplayName%', 'wp-security-audit-log' )
			),
		),
		__( 'Paid Memberships Pro Alerts', 'wp-security-audit-log' ) => array(

			array(
				8601,
				E_NOTICE,
				__( 'PMPro Order Added', 'wp-security-audit-log' ),
				__( 'PMPro Order (%ORDERID%) added for %User% (%USERID%) Level %MEMBERLEVEL% Amount %AMT% Status %STATUS% Type %TYPE% ', 'wp-security-audit-log' )
			),
			array(
				8602,
				E_CRITICAL,
				__( 'PMPro Order Deleted', 'wp-security-audit-log' ),
				__( 'PMPro Order (%ORDERID%) deleted for %User% (%USERID%) Level %MEMBERLEVEL% Amount %AMT% Status %STATUS% Type %TYPE% ', 'wp-security-audit-log' )
			),
			array(
				8603,
				E_WARNING,
				__( 'PMPro Order Updated', 'wp-security-audit-log' ),
				__( 'PMPro Order (%ORDERID%) updated %FIELD% old value "%OLDVALUE%"  changed to "%NEWVALUE%" ', 'wp-security-audit-log' )
			),

			array(
				8604,
				E_NOTICE,
				__( 'PMPro User membership level changed', 'wp-security-audit-log' ),
				__( 'PMPro Changing Level from %OLDLEVEL% to %NEWLEVEL% Start %STARTDATE% End %ENDDATE% CodeID %CODEID% for %USER%(%USERID%) ', 'wp-security-audit-log' )
			),
			array(
				8618,
				E_WARNING,
				__( 'PMPro User membership changed', 'wp-security-audit-log' ),
				__( 'PMPro %MSG% %LEVEL% Status %OLDSTATUS% to %NEWSTATUS% Enddate %OLDENDDATE% to %NEWENDDATE% for %USER%(%USERID%) ', 'wp-security-audit-log' )
			),

			array(
				8605,
				E_NOTICE,
				__( 'PMPro After Checkout', 'wp-security-audit-log' ),
				__( 'PMPro After Checkout %MSG% ORDER(%ORDERID%) Discount Code_ID(%CODEID%) Used for %User% (%USERID%) ', 'wp-security-audit-log' )
			),

			array(
				8606,
				E_WARNING,
				__( 'PMPro Discount Code Deleted', 'wp-security-audit-log' ),
				__( 'PMPro Delete Discount code (%CODEID%)  Code %CODE% Starts %STARTS% Expires %EXPIRES% Uses %USES% ', 'wp-security-audit-log' )
			),
			array(
				8607,
				E_NOTICE,
				__( 'PMPro Discount Code Saved', 'wp-security-audit-log' ),
				__( 'PMPro %MSG% Disoount Code - ID(%CODEID%) Code %CODE% Starts %START% Expires %EXPIRES% uses %USES% ', 'wp-security-audit-log' )
			),
			array(
				8617,
				E_NOTICE,
				__( 'PMPro Discount Code Added', 'wp-security-audit-log' ),
				__( 'PMPro %MSG% Disoount Code - ID(%CODEID%) Code %CODE% Starts %START% Expires %EXPIRES% uses %USES% ', 'wp-security-audit-log' )
			),

			array(
				8608,
				E_NOTICE,
				__( 'PMPro Discount Code Level Saved', 'wp-security-audit-log' ),
				__( 'PMPro Discount Code Level CodeID(%ORDERID%) LevelID %LEVELID% Pmt %PMT% Billing %BILL% Cycle# %CYCLENUM% Period %PERIOD% Limit %Limit% Amt %Amt% exp# %exp% ExpPeriod %ExpPeriod% ', 'wp-security-audit-log' )
			),

			array(
				8609,
				E_NOTICE,
				__( 'PMPro User membership level Saved', 'wp-security-audit-log' ),
				__( 'PMPro Save Member Level - %name%(%id%) [%description%] Confirm %confirm% Pmt %pmt% Amt %amt% Cycle# %cyclenum% Period %period% Limit %limit% Trial %tamt% Trial Limit %tlimit% Exp# %expnum% Exp Period %expperiod% Signups %signups% ', 'wp-security-audit-log' )
			),
			array(
				8610,
				E_CRITICAL,
				__( 'PMPro User membership level Deleted', 'wp-security-audit-log' ),
				__( 'PMPro Delete Member Level - %name%(%id%) [%description%] Confirm %confirm% Pmt %pmt% Amt %amt% Cycle# %cyclenum% Period %period% Limit %limit% Trial %tamt% Trial Limit %tlimit% Exp# %expnum% Exp Period %expperiod% Signups %signups% ', 'wp-security-audit-log' )
			),

			array(
				8611,
				E_WARNING,
				__( 'PMPro Subscription Cancelled', 'wp-security-audit-log' ),
				__( 'PMPro Subscription Cancelled: Last Order (%ORDERID%) %CODE% for %User% (%USERID%) Gateway %GATEWAY%  Txn ID %subscription_transaction_id% Amount %AMT% Status %STATUS% Type %TYPE% ', 'wp-security-audit-log' )
			),
			array(
				8612,
				E_WARNING,
				__( 'PMPro Subscription Expired', 'wp-security-audit-log' ),
				__( 'PMPro Subscription Expired: Last Order (%ORDERID%) %CODE% for %User% (%USERID%) Gateway %GATEWAY%  Txn ID %subscription_transaction_id% Amount %AMT% Status %STATUS% Type %TYPE% ', 'wp-security-audit-log' )
			),
			array(
				8613,
				E_NOTICE,
				__( 'PMPro Subscription IPN Processed', 'wp-security-audit-log' ),
				__( 'PMPro Subscription IPN Processed: for %User% (%USERID%) Gateway %GATEWAY%  IPN ID %IPNID% Amount %AMT% Membership-ID %MEMBERSHIPID% Status %STATUS% Type %TYPE%  ', 'wp-security-audit-log' )
			),
			array(
				8614,
				E_NOTICE,
				__( 'PMPro Subscription Pmt Completed', 'wp-security-audit-log' ),
				__( 'PMPro Subscription Pmt Completed:  Order (%ORDERID%) %CODE% for %User% (%USERID%) Gateway %GATEWAY%  Txn ID %subscription_transaction_id% Amount %AMT% Status %STATUS% Type %TYPE% ', 'wp-security-audit-log' )
			),
			array(
				8615,
				E_CRITICAL,
				__( 'PMPro Subscription Pmt Failed', 'wp-security-audit-log' ),
				__( 'PMPro Subscription Pmt Failed: Last Order (%ORDERID%) %CODE% for %User% (%USERID%) Gateway %GATEWAY%  Txn ID %subscription_transaction_id% Amount %AMT% Status %STATUS% Type %TYPE% ', 'wp-security-audit-log' )
			),
			array(
				8616,
				E_CRITICAL,
				__( 'PMPro Subscription Pmt Past Due', 'wp-security-audit-log' ),
				__( 'PMPro Subscription Pmt PastDue: Last Order (%ORDERID%) %CODE% for %User% (%USERID%) Gateway %GATEWAY%  Txn ID %subscription_transaction_id% Amount %AMT% Status %STATUS% Type %TYPE% ', 'wp-security-audit-log' )
			),

		)
	)
);
