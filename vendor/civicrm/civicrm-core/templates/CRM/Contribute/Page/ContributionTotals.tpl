{*
 +--------------------------------------------------------------------+
 | CiviCRM version 5                                                  |
 +--------------------------------------------------------------------+
 | Copyright CiviCRM LLC (c) 2004-2019                                |
 +--------------------------------------------------------------------+
 | This file is a part of CiviCRM.                                    |
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
*}
{*Table displays contribution totals for a contact or search result-set *}
{if $annual.count OR $contributionSummary.total.count OR $contributionSummary.cancel.count OR $contributionSummary.soft_credit.count}
    <table class="form-layout-compressed">

    {if $annual.count}
        <tr>
            <th class="contriTotalLeft right">{ts}Current Year-to-Date{/ts} &ndash; {$annual.amount}</th>
            <th class="right"> &nbsp; {ts}# Completed Contributions{/ts} &ndash; {$annual.count}</th>
            <th class="right contriTotalRight"> &nbsp; {ts}Avg Amount{/ts} &ndash; {$annual.avg}</th>
            {if $contributionSummary.cancel.amount}
                <td>&nbsp;</td>
            {/if}
        </tr>
    {/if}

    {if $contributionSummary }
      <tr>
          {if $contributionSummary.total.amount}
            <th class="contriTotalLeft right">{ts}Total{/ts} &ndash; {$contributionSummary.total.amount}</th>
            <th class="right"> &nbsp; {ts}# Completed{/ts} &ndash; {$contributionSummary.total.count}</th>
            <th class="right contriTotalRight"> &nbsp; {ts}Avg{/ts} &ndash; {$contributionSummary.total.avg}</th>
          {/if}
          {if $contributionSummary.cancel.amount}
            <th class="disabled right contriTotalRight"> &nbsp; {ts}Cancelled/Refunded{/ts} &ndash; {$contributionSummary.cancel.amount}</th>
          {/if}
      </tr>
      {if $contributionSummary.soft_credit.count}
        {include file="CRM/Contribute/Page/ContributionSoftTotals.tpl" softCreditTotals=$contributionSummary.soft_credit}
      {/if}
    {/if}

    </table>
{/if}