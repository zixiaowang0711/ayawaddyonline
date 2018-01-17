{**
* 2013 - 2017 HiPresta
*
* MODULE Facebook Connect
*
* @version   1.1.0
* @author    HiPresta <suren.mikaelyan@gmail.com>
* @link      http://www.hipresta.com
* @copyright HiPresta 2017
* @license   PrestaShop Addons license limitation
*
*}

{if $psv == 1.6}
    <form action="{$action nofilter}" method="get" class="box" style="margin:10px;">
        <h3 class="page-subheading">{l s='Enter your personal information in order to proceed' mod='hifacebookconnect'}</h3>
        <div class="form_content clearfix">
            <div class="hide display_error alert alert-warning" id="create_account_error" ></div>
            <div class="form-group">
                <label for="user_fname" class="fname {if $user_fname != '' && $user_lname != '' && $name_status == 'full'}hide{/if}">{l s='First Name*' mod='hifacebookconnect'}</label>
                <input name="user_fname" value="{$user_fname|escape:'htmlall':'UTF-8'}" type="{if $user_fname != '' && $user_lname != '' && $name_status == 'full'}hidden{else}text{/if}" class="fname form-control" placeholder="{l s='First name' mod='hifacebookconnect'}">
                <label for="user_lname" class="lname {if $user_fname != '' && $user_lname != '' && $name_status == 'full'}hide{/if}">{l s='Last Name*' mod='hifacebookconnect'}</label>
                <input name="user_lname" value="{$user_lname|escape:'htmlall':'UTF-8'}" type="{if $user_fname != '' && $user_lname != '' && $name_status == 'full'}hidden{else}text{/if}" class="lname form-control" placeholder="{l s='Last name' mod='hifacebookconnect'}">
                <label for="email_create">{l s='Email address*' mod='hifacebookconnect'}</label>
                <input name="email" type="text" class="form-control" placeholder="{l s='Email' mod='hifacebookconnect'}">
                <label class='hide hidden_pass' for="password">{l s='Password*' mod='hifacebookconnect'}</label>
                <input name="password" type="password" class="hide hidden_pass form-control" placeholder="{l s='Password' mod='hifacebookconnect'}">
                <input name="user_data_id" type="hidden" value="{$user_data_id|escape:'htmlall':'UTF-8'}">
                <input name="gender" type="hidden" value="{$gender|escape:'htmlall':'UTF-8'}">
                <input name="popup" type="hidden" value="{$popup|escape:'htmlall':'UTF-8'}">
            </div>
            <div class="submit">
                <button class="button btn btn-default button-medium" type="submit" id="activate_submit" name="activate_submit">
                    <span><i class="icon-user left"></i>{l s='Proceed' mod='hifacebookconnect'}</span>
                </button>
                <button class="hide link_my_account button btn btn-default button-medium" type="submit" id="link_my_account" name="link_my_account">
                    <span>{l s='Link to my account' mod='hifacebookconnect'}</span>
                </button>
                <button class="hide sc_back button btn btn-default button-medium" type="submit" id="sc_back" name="sc_back">
                    <span>{l s='Back' mod='hifacebookconnect'}</span>
                </button>
            </div>
        </div>
    </form>
{/if}
{if $psv == 1.5}
    <form action="{$action nofilter}" method="get" class="std" id="create-account_form">
        <fieldset>
            <h3>{l s='Enter your personal information in order to proceed' mod='hifacebookconnect'}</h3>
            <div class="form_content clearfix">
                <div class="hide display_error warning" id="create_account_error"></div>
                <p class="text fname {if $user_fname != '' && $user_lname != '' && $name_status == 'full'}hide{/if}">
                    <label for="user_fname" class="">{l s='First Name*' mod='hifacebookconnect'}</label>
                    <input name="user_fname" value="{$user_fname|escape:'htmlall':'UTF-8'}" type="text" class="fname form-control" placeholder="{l s='First name' mod='hifacebookconnect'}">
                </p>
                <p class="text lname {if $user_fname != '' && $user_lname != '' && $name_status == 'full'}hide{/if}">
                    <label for="user_lname" class="">{l s='Last Name*' mod='hifacebookconnect'}</label>
                    <input name="user_lname" value="{$user_lname|escape:'htmlall':'UTF-8'}" type="text" class="lname form-control" placeholder="{l s='Last name' mod='hifacebookconnect'}">
                </p>
                <p class="text">
                    <label for="email_create">{l s='Email address*' mod='hifacebookconnect'}</label>
                    <input name="email" type="text" class="form-control" placeholder="{l s='Email' mod='hifacebookconnect'}">
                </p>
                <p class="text hide hidden_pass">
                    <label class='' for="password">{l s='Password*' mod='hifacebookconnect'}</label>
                    <input name="password" type="password" class="hide hidden_pass form-control" placeholder="{l s='Password' mod='hifacebookconnect'}">
                </p>
                <input name="user_data_id" type="hidden" value="{$user_data_id|escape:'htmlall':'UTF-8'}">
                <input name="gender" type="hidden" value="{$gender|escape:'htmlall':'UTF-8'}">
                <input name="popup" type="hidden" value="{$popup|escape:'htmlall':'UTF-8'}">
                <p class="submit">
                    <input type="submit" id="activate_submit" name="activate_submit" class="button_large" value="{l s='Proceed' mod='hifacebookconnect'}" />
                    <input type="submit" id="link_my_account" name="link_my_account" class="button_large hide link_my_account" value="{l s='Link to my account' mod='hifacebookconnect'}" />
                    <input type="submit" id="sc_back" name="sc_back" class="button_large hide sc_back" value="{l s='Back' mod='hifacebookconnect'}" />
                </p>
            </div>
        </fieldset>
    </form>
{/if}