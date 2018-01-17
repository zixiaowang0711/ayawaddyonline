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

{extends file='page.tpl'}
{block name='page_content'}
    <div class="form_content clearfix">
        <form action="{$action nofilter}" method="get" class="box" style="margin:10px;">
            <h3>{l s='Enter your personal information in order to proceed' mod='hifacebookconnect'}</h3>
            <div class="hide display_error alert alert-warning" id="create_account_error" ></div>
            <div class="form-group row ">
                <label for="user_fname" class="col-md-3 form-control-label required fname {if $user_fname != '' && $user_lname != '' && $name_status == 'full'}hide{/if}">{l s='First Name*' mod='hifacebookconnect'}</label>
                <div class="col-md-6">
                    <input name="user_fname" value="{$user_fname|escape:'htmlall':'UTF-8'}" type="{if $user_fname != '' && $user_lname != '' && $name_status == 'full'}hidden{else}text{/if}" class="fname form-control" placeholder="{l s='First name' mod='hifacebookconnect'}">
                </div>
            </div>
            <div class="form-group row ">
                <label for="user_lname" class="form-control-label col-md-3 required lname {if $user_fname != '' && $user_lname != '' && $name_status == 'full'}hide{/if}">{l s='Last Name*' mod='hifacebookconnect'}</label>
                <div class="col-md-6">
                    <input name="user_lname" value="{$user_lname|escape:'htmlall':'UTF-8'}" type="{if $user_fname != '' && $user_lname != '' && $name_status == 'full'}hidden{else}text{/if}" class="lname form-control" placeholder="{l s='Last name' mod='hifacebookconnect'}">
                </div>
            </div>
            <div class="form-group row ">
                <label for="email_create" class="col-md-3 form-control-label required">{l s='Email address*' mod='hifacebookconnect'}</label>
                <div class="col-md-6">
                    <input name="email" type="text" class="form-control" placeholder="{l s='Email' mod='hifacebookconnect'}">
                </div>
            </div>
            <div class="form-group row ">
                <label class='col-md-3 form-control-label required hide hidden_pass' for="password">{l s='Password*' mod='hifacebookconnect'}</label>
                <div class="col-md-6">
                    <input name="password" type="password" class="hide hidden_pass form-control" value="" placeholder="{l s='Password' mod='hifacebookconnect'}">
                </div>
            </div>
            <div class="form-group">
                <input name="user_data_id" type="hidden" value="{$user_data_id|escape:'htmlall':'UTF-8'}">
                <input name="gender" type="hidden" value="{$gender|escape:'htmlall':'UTF-8'}">
                <input name="popup" type="hidden" value="{$popup|escape:'htmlall':'UTF-8'}">
            </div>
            <hr>
            <footer class="form-footer text-xs-center clearfix col-md-9">
                <button class="btn btn-primary" type="submit" id="activate_submit" name="activate_submit">
                    {l s='Proceed' mod='hifacebookconnect'}
                </button>
                <button class="hide link_my_account btn btn-primary" type="submit" id="link_my_account" name="link_my_account">
                    {l s='Link to my account' mod='hifacebookconnect'}
                </button>
                <button class="hide sc_back btn btn-primary" type="submit" id="sc_back" name="sc_back">
                    {l s='Back' mod='hifacebookconnect'}
                </button>
            </footer>
        </form>
    </div>
{/block}
