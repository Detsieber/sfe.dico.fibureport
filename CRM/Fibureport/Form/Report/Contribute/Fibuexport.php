<?php
/*
 +--------------------------------------------------------------------+
 | CiviCRM version 4.6                                                |
 +--------------------------------------------------------------------+
 | Copyright CiviCRM LLC (c) 2004-2015                                |
 + This file is a part of CiviCRM.                                    |
 |                                                                    |
 | CiviCRM is free software; you can copy, modify, and distribute it  |
 | under the terms of the GNU Affero General Public License           |
 | Version 3, 19 November 2007 and the CiviCRM Licensing Exception.   |
 |                                                                    |
 | CiviCRM is distributed in the hope that it will be useful, but     |
 | WITHOUT ANY WARRANTY; without even the implied warranty of         |
 | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.               |
 | See the GNU Affero General Public License for more details.        |
 |                                                                    |
 | You should have received a copy of the GNU Affero General Public   |
 | License and the CiviCRM Licensing Exception along                  |
 | with this program; if not, contact CiviCRM LLC                     |
 | at info[AT]civicrm[DOT]org. If you have questions about the        |
 | GNU Affero General Public License or the licensing of CiviCRM,     |
 | see the CiviCRM license FAQ at http://civicrm.org/licensing        |
 +--------------------------------------------------------------------+
 */

/**
 *
 * @package CRM
 * @copyright CiviCRM LLC (c) 2004-2015
 * $Id$
 *
 */
class CRM_Fibureport_Form_Report_Contribute_Fibuexport extends CRM_Report_Form {
  protected $_addressField = FALSE;

  protected $_emailField = FALSE;

  protected $_summary = NULL;

  /**
   */
  public function __construct() {
    $this->_autoIncludeIndexedFieldsAsOrderBys = 1;
    $this->_columns = array(
      'civicrm_contribution' => array(
        'dao' => 'CRM_Contribute_DAO_Contribution',
        'fields' => array(
          'receive_date' => array(
            'title' => 'Datum', 
            'type' => CRM_Utils_Type::T_STRING,
            'required' => TRUE,
          ),
          'invoice_id' => array(
            'title' => 'Belegnr.', 
            'required' => TRUE,
          ),
        ),
        'order_bys' => array(
          'receive_date' => array(
            'name' => 'receive_date',
            'title' => 'Eingangsdatum',
            'default' => '1',
            'default_weight' => '0',
            'default_order' => 'ASC' 
	    )),
        'filters' => array(
          'receive_date' => array('operatorType' => CRM_Report_Form::OP_DATE),
          'contribution_status_id' => array(
            'title' => ts('Contribution Status'),
            'operatorType' => CRM_Report_Form::OP_MULTISELECT,
            'options' => CRM_Contribute_PseudoConstant::contributionStatus(),
            'default' => array(1),
          ),
        ),
      ), 

      'civicrm_contact' => array(
        'dao' => 'CRM_Contact_DAO_Contact',
        'fields' => array(
          'sort_name' => array(
            'title' => 'Buchungstext',
            'required' => TRUE,
            'no_repeat' => TRUE,
          ),
          'first_name' => array(
            'title' => ts('First Name'),
            'no_display' => TRUE,
          ),
          'middle_name' => array(
            'title' => ts('Middle Name'),
            'no_display' => TRUE,
          ),
          'last_name' => array(
            'title' => ts('Last Name'),
            'no_display' => TRUE,
          ),
          'id' => array(
            'no_display' => TRUE,
          ),
        ),
        'grouping' => 'contact-fields',
        'order_bys' => array(
          'sort_name' => array(
            'title' => ts('Last Name, First Name'),
            'default' => '1',
            'default_weight' => '1',
            'default_order' => 'ASC',
          ),
          'first_name' => array(
            'name' => 'first_name',
            'title' => ts('First Name'),
          ),
          'contact_type' => array(
            'title' => ts('Contact Type'),
          ),
          'contact_sub_type' => array(
            'title' => ts('Contact Subtype'),
          ),
        ), 
      ),
      'civicrm_membership' => array(
        'dao' => 'CRM_Member_DAO_Membership',
        'fields' => array(
          'id' => array(
            'title' => ts('Membership #'),
            'no_display' => TRUE,
            'required' => TRUE,
          ),
        ),
      ), 
      'civicrm_financial_account' => array(
        'dao' => 'CRM_Financial_DAO_FinancialAccount',
        'fields' => array(
          'debit_accounting_code' => array(
            'title' => 'Soll',
            'name' => 'accounting_code',
            'alias' => 'financial_account_civireport_debit',
            'default' => TRUE,
            'required' => TRUE,
          ),
          'credit_accounting_code' => array(
            'title' => 'Haben',
            'name' => 'accounting_code',
            'alias' => 'financial_account_civireport_credit',
            'default' => TRUE,
            'required' => TRUE,
          ),
          'debit_name' => array(
            'title' => ts('Financial Account Name - Debit'),
            'name' => 'name',
            'alias' => 'financial_account_civireport_debit',
            'default' => FALSE,
            'no_display' => TRUE,
          ),
          'credit_name' => array(
            'title' => ts('Financial Account Name - Credit'),
            'name' => 'name',
            'alias' => 'financial_account_civireport_credit',
            'default' => FALSE,
            'no_display' => TRUE,
          ),
        ),
        'filters' => array(
          'debit_accounting_code' => array(
            'title' => ts('Financial Account Code - Debit'),
            'operatorType' => CRM_Report_Form::OP_MULTISELECT,
            'options' => CRM_Contribute_PseudoConstant::financialAccount(NULL, NULL, 'accounting_code', 'accounting_code'),
            'name' => 'accounting_code',
            'alias' => 'financial_account_civireport_debit',
          ),
          'credit_accounting_code' => array(
            'title' => ts('Financial Account Code - Credit'),
            'operatorType' => CRM_Report_Form::OP_MULTISELECT,
            'options' => CRM_Contribute_PseudoConstant::financialAccount(NULL, NULL, 'accounting_code', 'accounting_code'),
          ),
          'debit_name' => array(
            'title' => ts('Financial Account Name - Debit'),
            'operatorType' => CRM_Report_Form::OP_MULTISELECT,
            'options' => CRM_Contribute_PseudoConstant::financialAccount(),
            'name' => 'id',
            'alias' => 'financial_account_civireport_debit',
          ),
          'credit_name' => array(
            'title' => ts('Financial Account Name - Credit'),
            'operatorType' => CRM_Report_Form::OP_MULTISELECT,
            'options' => CRM_Contribute_PseudoConstant::financialAccount(),
          ),
        ),
      ),
/*      'civicrm_batch' => array(
        'dao' => 'CRM_Batch_DAO_Batch',
        'fields' => array(
          'title' => array(
            'title' => ts('Batch Title'),
            'alias' => 'batch',
            'default' => FALSE,
          ),
          'name' => array(
            'title' => ts('Batch Name'),
            'alias' => 'batch',
            'default' => TRUE,
          ),
        ),
      ), */
      'civicrm_entity_financial_trxn' => array(
        'dao' => 'CRM_Financial_DAO_EntityFinancialTrxn',
        'fields' => array(
          'amount' => array(
            'title' => ts('Amount'),
            'default' => TRUE,
            'required' => TRUE,
            'type' => CRM_Utils_Type::T_STRING,
          ),
        ),
        'filters' => array(
          'amount' => array('title' => ts('Amount')),
        ),
      ),
      'civicrm_line_item' => array(
        'dao' => 'CRM_Price_DAO_LineItem',
        'fields' => array(
          'financial_type_id' => array(
            'title' => 'Steuerschlüssel',
            'default' => TRUE,
          ),
        ),
        'filters' => array(
          'financial_type_id' => array(
            'title' => 'Steuerschlüssel', 
            'operatorType' => CRM_Report_Form::OP_MULTISELECT,
            'options' => CRM_Contribute_PseudoConstant::financialType(),
            'required' => TRUE,
          ),
        ),
        'order_bys' => array(
          'financial_type_id' => array('title' => ts('Financial Type')),
        ),
      ),
      'civicrm_financial_trxn' => array(
        'dao' => 'CRM_Financial_DAO_FinancialTrxn',
        'fields' => array(
/*          'check_number' => array(
            'title' => ts('Cheque #'),
            'default' => TRUE,
          ),
          'payment_instrument_id' => array(
            'title' => ts('Payment Instrument'),
            'default' => TRUE,
          ),
          'currency' => array(
            'required' => TRUE,
            'no_display' => TRUE,
          ), */

          'trxn_date' => array(
            'title' => ts('Transaction Date'),
            'default' => TRUE,
          ),

/*          'trxn_date' => array(
            'title' => 'Kostenstelle',
            'default' => TRUE,
            'required' => TRUE,
          ), */
/*          'trxn_id' => array(
            'title' => ts('Trans #'),
            'default' => TRUE,
          ), */
        ),
        'filters' => array(
          'payment_instrument_id' => array(
            'title' => ts('Payment Instrument'),
            'operatorType' => CRM_Report_Form::OP_MULTISELECT,
            'options' => CRM_Contribute_PseudoConstant::paymentInstrument(),
          ),
          'currency' => array(
            'title' => 'Currency',
            'operatorType' => CRM_Report_Form::OP_MULTISELECT,
            'options' => CRM_Core_OptionGroup::values('currencies_enabled'),
            'default' => NULL,
            'type' => CRM_Utils_Type::T_STRING,
          ),
          'trxn_date' => array(
            'title' => ts('Transaction Date'),
            'operatorType' => CRM_Report_Form::OP_DATE,
            'type' => CRM_Utils_Type::T_DATE,
          ),
        ),
        'order_bys' => array(
          'payment_instrument_id' => array('title' => ts('Payment Instrument')),
        ),
      ),
    );

    $this->_groupFilter = TRUE;
    $this->_tagFilter = TRUE;
    parent::__construct();
  }

  public function preProcess() {
    parent::preProcess();
  }

  public function select() {
    $select = array();

    $this->_columnHeaders = array();
    foreach ($this->_columns as $tableName => $table) {
      if (array_key_exists('fields', $table)) {
        foreach ($table['fields'] as $fieldName => $field) {
          if (!empty($field['required']) ||
            !empty($this->_params['fields'][$fieldName])
          ) {
            switch ($fieldName) {
              case 'credit_accounting_code':
                $select[] = " CASE
                            WHEN {$this->_aliases['civicrm_financial_trxn']}.from_financial_account_id IS NOT NULL
                            THEN  {$this->_aliases['civicrm_financial_account']}_credit_1.accounting_code
                            ELSE  {$this->_aliases['civicrm_financial_account']}_credit_2.accounting_code
                            END AS civicrm_financial_account_credit_accounting_code ";
                break;

              case 'amount':
                $select[] = " CASE
                            WHEN  {$this->_aliases['civicrm_entity_financial_trxn']}_item.entity_id IS NOT NULL
                            THEN {$this->_aliases['civicrm_entity_financial_trxn']}_item.amount
                            ELSE {$this->_aliases['civicrm_entity_financial_trxn']}.amount
                            END AS civicrm_entity_financial_trxn_amount ";
                break;

              case 'credit_name':
                $select[] = " CASE
                            WHEN {$this->_aliases['civicrm_financial_trxn']}.from_financial_account_id IS NOT NULL
                            THEN  {$this->_aliases['civicrm_financial_account']}_credit_1.name
                            ELSE  {$this->_aliases['civicrm_financial_account']}_credit_2.name
                            END AS civicrm_financial_account_credit_name ";
                break;

              default:
                $select[] = "{$field['dbAlias']} as {$tableName}_{$fieldName}";
                break;
            }
            $this->_columnHeaders["{$tableName}_{$fieldName}"]['title'] = $field['title'];
            $this->_columnHeaders["{$tableName}_{$fieldName}"]['type'] = CRM_Utils_Array::value('type', $field);
          }
        }
      }
    }

    $this->_select = 'SELECT ' . implode(', ', $select) . ' ';
  }

  public function from() {
    $this->_from = NULL;

    $this->_from = "FROM  civicrm_contact {$this->_aliases['civicrm_contact']} {$this->_aclFrom}
              INNER JOIN civicrm_contribution {$this->_aliases['civicrm_contribution']}
                    ON {$this->_aliases['civicrm_contact']}.id = {$this->_aliases['civicrm_contribution']}.contact_id AND
                         {$this->_aliases['civicrm_contribution']}.is_test = 0
              LEFT JOIN civicrm_membership_payment payment
                    ON ( {$this->_aliases['civicrm_contribution']}.id = payment.contribution_id )
              LEFT JOIN civicrm_membership {$this->_aliases['civicrm_membership']}
                    ON payment.membership_id = {$this->_aliases['civicrm_membership']}.id
              LEFT JOIN civicrm_entity_financial_trxn {$this->_aliases['civicrm_entity_financial_trxn']}
                    ON ({$this->_aliases['civicrm_contribution']}.id = {$this->_aliases['civicrm_entity_financial_trxn']}.entity_id AND
                        {$this->_aliases['civicrm_entity_financial_trxn']}.entity_table = 'civicrm_contribution')
              LEFT JOIN civicrm_financial_trxn {$this->_aliases['civicrm_financial_trxn']}
                    ON {$this->_aliases['civicrm_financial_trxn']}.id = {$this->_aliases['civicrm_entity_financial_trxn']}.financial_trxn_id
              LEFT JOIN civicrm_financial_account {$this->_aliases['civicrm_financial_account']}_debit
                    ON {$this->_aliases['civicrm_financial_trxn']}.to_financial_account_id = {$this->_aliases['civicrm_financial_account']}_debit.id
              LEFT JOIN civicrm_financial_account {$this->_aliases['civicrm_financial_account']}_credit_1
                    ON {$this->_aliases['civicrm_financial_trxn']}.from_financial_account_id = {$this->_aliases['civicrm_financial_account']}_credit_1.id
              LEFT JOIN civicrm_entity_financial_trxn {$this->_aliases['civicrm_entity_financial_trxn']}_item
                    ON ({$this->_aliases['civicrm_financial_trxn']}.id = {$this->_aliases['civicrm_entity_financial_trxn']}_item.financial_trxn_id AND
                        {$this->_aliases['civicrm_entity_financial_trxn']}_item.entity_table = 'civicrm_financial_item')
              LEFT JOIN civicrm_financial_item fitem
                    ON fitem.id = {$this->_aliases['civicrm_entity_financial_trxn']}_item.entity_id
              LEFT JOIN civicrm_financial_account {$this->_aliases['civicrm_financial_account']}_credit_2
                    ON fitem.financial_account_id = {$this->_aliases['civicrm_financial_account']}_credit_2.id
              LEFT JOIN civicrm_line_item {$this->_aliases['civicrm_line_item']}
                    ON  fitem.entity_id = {$this->_aliases['civicrm_line_item']}.id AND fitem.entity_table = 'civicrm_line_item' ";
    if ($this->isTableSelected('civicrm_batch')) {
      $this->_from .= "LEFT JOIN civicrm_entity_batch ent_batch
        ON  {$this->_aliases['civicrm_financial_trxn']}.id = ent_batch.entity_id AND ent_batch.entity_table = 'civicrm_financial_trxn' 
      LEFT JOIN civicrm_batch batch
        ON  ent_batch.batch_id = batch.id";
    }
  }

  public function orderBy() {
    parent::orderBy();

    // please note this will just add the order-by columns to select query, and not display in column-headers.
    // This is a solution to not throw fatal errors when there is a column in order-by, not present in select/display columns.
    foreach ($this->_orderByFields as $orderBy) {
      if (!array_key_exists($orderBy['name'], $this->_params['fields']) &&
        empty($orderBy['section'])
      ) {
        $this->_select .= ", {$orderBy['dbAlias']} as {$orderBy['tplField']}";
      }
    }
  }

  public function where() {
    foreach ($this->_columns as $tableName => $table) {
      if (array_key_exists('filters', $table)) {
        foreach ($table['filters'] as $fieldName => $field) {
          $clause = NULL;
          if ($fieldName == 'credit_accounting_code') {
            $field['dbAlias'] = "CASE
              WHEN financial_trxn_civireport.from_financial_account_id IS NOT NULL
              THEN  financial_account_civireport_credit_1.accounting_code
              ELSE  financial_account_civireport_credit_2.accounting_code
              END";
          }
          elseif ($fieldName == 'credit_name') {
            $field['dbAlias'] = "CASE
              WHEN financial_trxn_civireport.from_financial_account_id IS NOT NULL
              THEN  financial_account_civireport_credit_1.id
              ELSE  financial_account_civireport_credit_2.id
              END";
          }
          if (CRM_Utils_Array::value('type', $field) & CRM_Utils_Type::T_DATE) {
            $relative = CRM_Utils_Array::value("{$fieldName}_relative", $this->_params);
            $from = CRM_Utils_Array::value("{$fieldName}_from", $this->_params);
            $to = CRM_Utils_Array::value("{$fieldName}_to", $this->_params);

            $clause = $this->dateClause($field['name'], $relative, $from, $to, $field['type']);
          }
          else {
            $op = CRM_Utils_Array::value("{$fieldName}_op", $this->_params);
            if ($op) {
              $clause = $this->whereClause($field,
                $op,
                CRM_Utils_Array::value("{$fieldName}_value", $this->_params),
                CRM_Utils_Array::value("{$fieldName}_min", $this->_params),
                CRM_Utils_Array::value("{$fieldName}_max", $this->_params)
              );
            }
          }
          if (!empty($clause)) {
            $clauses[] = $clause;
          }
        }
      }
    }
    if (empty($clauses)) {
      $this->_where = 'WHERE ( 1 )';
    }
    else {
      $this->_where = 'WHERE ' . implode(' AND ', $clauses);
    }
  }

  public function postProcess() {
    // get the acl clauses built before we assemble the query
    $this->buildACLClause($this->_aliases['civicrm_contact']);
    parent::postProcess();
  }

  public function groupBy() {
    $this->_groupBy = " GROUP BY  {$this->_aliases['civicrm_entity_financial_trxn']}.id, {$this->_aliases['civicrm_line_item']}.id ";
  }

  /**
   * @param $rows
   *
   * @return array
   */
  public function statistics(&$rows) {
    $statistics = parent::statistics($rows);
    $tempTableName = CRM_Core_DAO::createTempTableName('civicrm_contribution');
    $select = "SELECT {$this->_aliases['civicrm_contribution']}.id, {$this->_aliases['civicrm_entity_financial_trxn']}.id as trxnID, {$this->_aliases['civicrm_contribution']}.currency,
               CASE
                 WHEN {$this->_aliases['civicrm_entity_financial_trxn']}_item.entity_id IS NOT NULL
                 THEN {$this->_aliases['civicrm_entity_financial_trxn']}_item.amount
                 ELSE {$this->_aliases['civicrm_entity_financial_trxn']}.amount
               END as amount
";

    $tempQuery = "CREATE TEMPORARY TABLE {$tempTableName} CHARACTER SET utf8 COLLATE utf8_unicode_ci AS
                  {$select} {$this->_from} {$this->_where} {$this->_groupBy} ";
    CRM_Core_DAO::executeQuery($tempQuery);

    $sql = "SELECT COUNT(trxnID) as count, SUM(amount) as amount, currency
            FROM {$tempTableName}
            GROUP BY currency";
    $dao = CRM_Core_DAO::executeQuery($sql);
    $amount = $avg = array();
    while ($dao->fetch()) {
      $amount[] = CRM_Utils_Money::format($dao->amount, $dao->currency);
      $avg[] = CRM_Utils_Money::format(round(($dao->amount /
        $dao->count), 2), $dao->currency);
    }

    $statistics['counts']['amount'] = array(
      'value' => implode(', ', $amount),
      'title' => 'Total Amount',
      'type' => CRM_Utils_Type::T_STRING,
    );
    $statistics['counts']['avg'] = array(
      'value' => implode(', ', $avg),
      'title' => 'Average',
      'type' => CRM_Utils_Type::T_STRING,
    );
    return $statistics;
  }

  /**
   * Alter display of rows.
   *
   * Iterate through the rows retrieved via SQL and make changes for display purposes,
   * such as rendering contacts as links.
   *
   * @param array $rows
   *   Rows generated by SQL, with an array for each row.
   */
  public function alterDisplay(&$rows) {
    $contributionTypes = CRM_Contribute_PseudoConstant::financialType();
    $paymentInstruments = CRM_Contribute_PseudoConstant::paymentInstrument();
    $contributionStatus = CRM_Contribute_PseudoConstant::contributionStatus();
    foreach ($rows as $rowNum => $row) {
      // display receive date in German format
      if (array_key_exists('civicrm_contribution_receive_date', $row)) {
        $receiveDate = strval(CRM_Utils_Date::customFormat($row['civicrm_financial_trxn_trxn_date'], '%d.%m.%Y')) ;
        $rows[$rowNum]['civicrm_contribution_receive_date'] = substr($receiveDate,0,10) ;
        $entryFound = TRUE;
      }

      // fill invoice id with receive date
      if (array_key_exists('civicrm_contribution_receive_date', $row)) {
        $invoice_id = $row['civicrm_financial_trxn_trxn_date'];
        if ($invoice_id) {
          $rows[$rowNum]['civicrm_contribution_invoice_id'] = CRM_Utils_Date::customFormat($invoice_id, '%Y%m%d');
        }
        $entryFound = TRUE;
      }

      // handle financial type id
      if ($value = CRM_Utils_Array::value('civicrm_line_item_financial_type_id', $row)) {
        $rows[$rowNum]['civicrm_line_item_financial_type_id'] = $contributionTypes[$value];
      }


      // Formatting of amount ("xxx,yy")
      if ($value = CRM_Utils_Array::value('civicrm_entity_financial_trxn_amount', $row)) {
        $rows[$rowNum]['civicrm_entity_financial_trxn_amount'] = 
		strtr(strval($rows[$rowNum]['civicrm_entity_financial_trxn_amount']), ".", ",");
      } 


      // convert display name to posting text
        $rows[$rowNum]['civicrm_contact_sort_name'] = $rows[$rowNum]['civicrm_contact_sort_name']
		. " - " . $rows[$rowNum]['civicrm_line_item_financial_type_id'];
      

      // Re-use (hard code) column "contribution type" as "Steuerschlüssel"
      if ($value = CRM_Utils_Array::value('civicrm_line_item_financial_type_id', $row)) {
	$accounting_code = $rows[$rowNum]['civicrm_financial_account_credit_accounting_code'];
        if ($accounting_code=="8032"){ 
	      $rows[$rowNum]['civicrm_line_item_financial_type_id'] = "3";
              }
        else {$rows[$rowNum]['civicrm_line_item_financial_type_id'] = "0";
              }

      }

/*      // Re-use (hard code) column "Transaction Date" as "empty"
      if ($value = CRM_Utils_Array::value('civicrm_financial_trxn_trxn_date', $row)) {
        $rows[$rowNum]['civicrm_financial_trxn_trxn_date'] = "13";
      }
*/

      // handle contribution status id
      if ($value = CRM_Utils_Array::value('civicrm_contribution_contribution_status_id', $row)) {
        $rows[$rowNum]['civicrm_contribution_contribution_status_id'] = $contributionStatus[$value];
      }

      // handle payment instrument id
      if ($value = CRM_Utils_Array::value('civicrm_financial_trxn_payment_instrument_id', $row)) {
        $rows[$rowNum]['civicrm_financial_trxn_payment_instrument_id'] = $paymentInstruments[$value];
      }



    }
  }

}
