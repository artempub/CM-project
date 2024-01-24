@extends('backend.layouts.app')

@section('content')

<section class="content-header">
    @include('backend.partials.messages')
</section>

<section class="content">

    <div class="bg-light lter b-b wrapper-md br-wreapper">
        <a href="https://slotparty.me/backend/">Home</a>
        > Faq
    </div>

    <div class="wrapper-md" ng-controller="FormDemoCtrl">
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-default">
                    <div class="panel-heading font-bold">
                       FAQ
                        
                    </div>
                    <div class="panel-body">
                        <div class="">
                            <div class="panel-group" id="accordion">
                                <div class="faqHeader">General questions</div>
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#001">How to change password and timezone?</a>
                                        </h4>
                                    </div>
                                    <div id="001" class="panel-collapse collapse">
                                        <div class="panel-body">
                                            <strong>#1 Go to profile page</strong>
                                            <br>
                                            <br>
                                            <br>
                                            <img src="https://netxo.gapi.lol/img/faq/password.png" alt="..." class="img-rounded img-responsive">
                                            <p></p>
                                            <strong>#2 Submit your old password and new password and save</strong>
                                            <br>
                                            <br>
                                            <br>
                                            <img src="https://netxo.gapi.lol/img/faq/password1.png" alt="..." class="img-rounded img-responsive">
                                            <p></p>
                                            <strong>#3 Change time zone select your time zone fro drop down menu and save</strong>
                                            <br>
                                            <br>
                                            <br>
                                            <img src="https://netxo.gapi.lol/img/faq/password3.png" alt="..." class="img-rounded">
                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#002">How to create operator?</a>
                                        </h4>
                                    </div>
                                    <div id="002" class="panel-collapse collapse">
                                        <div class="panel-body">
                                            <strong>#1 Click the “New Operator” from left side menu.</strong>
                                            <br>
                                            <br>
                                            <br>
                                            <img src="https://netxo.gapi.lol/img/faq/001.png" alt="..." class="img-rounded img-responsive">
                                            <p></p>
                                            <strong>#2 Fill in username and password for the new operator. Password must be at least 6 characters long.</strong>
                                            <br>
                                            <br>
                                            <br>
                                            <img src="https://netxo.gapi.lol/img/faq/0011.png" alt="..." class="img-rounded img-responsive">
                                            <p></p>
                                            <strong>#3 Permissions. In the same tab just below you will see the permissions you can leave as it is</strong>
                                            <br>
                                            <br>
                                            <br>
                                            <img src="https://netxo.gapi.lol/img/faq/0012.png" alt="..." class="img-rounded img-responsive">
                                            <p></p>
                                            <strong>#4 Lobby. Here you can give a lobby that can be used by this new operator and submit create</strong>
                                            <br>
                                            <br>
                                            <br>
                                            <img src="https://netxo.gapi.lol/img/faq/0013.png" alt="..." class="img-rounded img-responsive">
                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#003">How to add credits to an operator?</a>
                                        </h4>
                                    </div>
                                    <div id="003" class="panel-collapse collapse">
                                        <div class="panel-body">
                                            <strong>#1 Press Operators from left side menu.</strong>
                                            <br>
                                            <br>
                                            <br>
                                            <img src="https://netxo.gapi.lol/img/faq/003-1.png" alt="..." class="img-rounded img-responsive">
                                            <p></p>
                                            <strong>#2 Choose the operator from the operator list.</strong>
                                            <br>
                                            <br>
                                            <br>
                                            <img src="https://netxo.gapi.lol/img/faq/003-2.png" alt="..." class="img-rounded img-responsive">
                                            <p></p>
                                            <strong>#3 Input the amount of credits that you want to transfer and press the + button</strong>
                                            <br>
                                            <br>
                                            <br>
                                            <img src="https://netxo.gapi.lol/img/faq/003-3.png" alt="..." class="img-rounded img-responsive">
                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#004">What is the accounts Limit and how to add accounts?</a>
                                        </h4>
                                    </div>
                                    <div id="004" class="panel-collapse collapse">
                                        <div class="panel-body">
                                            <strong>#1 Accounts limit is the number of account that an operator or a cashier can create. To add accounts, you input the amount that you want to transfer in the “Accounts limit” field and you press the + button
                                            </strong>
                                            <br>
                                            <br>
                                            <br>
                                            <img src="https://netxo.gapi.lol/img/faq/003-1.png" alt="..." class="img-rounded img-responsive">
                                            <p></p>
                                            <br>
                                            <br>
                                            <br>
                                            <img src="https://netxo.gapi.lol/img/faq/003-2.png" alt="..." class="img-rounded img-responsive">
                                            <p></p>
                                            <br>
                                            <br>
                                            <br>
                                            <img src="https://netxo.gapi.lol/img/faq/004-1.png" alt="..." class="img-rounded img-responsive">
                                            <p></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#005">How to remove the permission of creating other operators for one operator?</a>
                                        </h4>
                                    </div>
                                    <div id="005" class="panel-collapse collapse">
                                        <div class="panel-body">
                                            <strong>#1 Press Operators from left side menu.
                                            </strong>
                                            <br>
                                            <br>
                                            <br>
                                            <img src="https://netxo.gapi.lol/img/faq/003-1.png" alt="..." class="img-rounded img-responsive">
                                            <br>
                                            <strong>#2 Choose the operator from the operator list.
                                            </strong>
                                            <br>
                                            <br>
                                            <br>
                                            <img src="https://netxo.gapi.lol/img/faq/003-2.png" alt="..." class="img-rounded img-responsive">
                                            <br>
                                            <strong>#3 Go to the Permissions tab and disable the option “Allow create managers”.
                                            </strong>
                                            <br>
                                            <br>
                                            <br>
                                            <img src="https://netxo.gapi.lol/img/faq/005-3.png" alt="..." class="img-rounded img-responsive">
                                            <p></p>
                                            <br>
                                            <br>
                                            <br>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#006">How to create new shop?</a>
                                        </h4>
                                    </div>
                                    <div id="006" class="panel-collapse collapse">
                                        <div class="panel-body">
                                            <strong>#1 From the left side panel press “New Shop”
                                            </strong>
                                            <br>
                                            <br>
                                            <br>
                                            <img src="https://netxo.gapi.lol/img/faq/006-1.png" alt="..." class="img-rounded img-responsive">
                                            <br>
                                            <strong>#2 Fill in username and password for the new shop account. Password must be at least 6 characters long..
                                            </strong>
                                            <br>
                                            <br>
                                            <br>
                                            <img src="https://netxo.gapi.lol/img/faq/006-2.png" alt="..." class="img-rounded img-responsive">
                                            <br>
                                            <strong>#3 Assign to which operator do you want this shop to belong to (this is the most important part of creating the new shop, because you cannot reassign the shop to another operator).
                                            </strong>
                                            <br>
                                            <br>
                                            <br>
                                            <img src="https://netxo.gapi.lol/img/faq/006-3.png" alt="..." class="img-rounded img-responsive">
                                            <p></p>
                                            <br>
                                            <br>
                                            <br>
                                            <strong>#4 Final step select lobby for this shop and submit create.
                                            </strong>
                                            <br>
                                            <br>
                                            <br>
                                            <img src="https://netxo.gapi.lol/img/faq/006-4.png" alt="..." class="img-rounded img-responsive">
                                            <p></p>
                                            <br>
                                            <br>
                                            <br>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#007">How to add credit to a shop?</a>
                                        </h4>
                                    </div>
                                    <div id="007" class="panel-collapse collapse">
                                        <div class="panel-body">
                                            <strong>#1 From the left side panel press “Shops”
                                            </strong>
                                            <br>
                                            <br>
                                            <br>
                                            <img src="https://netxo.gapi.lol/img/faq/007-1.png" alt="..." class="img-rounded img-responsive">
                                            <br>
                                            <strong>#2 Choose the shop from the shop list
                                            </strong>
                                            <br>
                                            <br>
                                            <br>
                                            <img src="https://netxo.gapi.lol/img/faq/007-2.png" alt="..." class="img-rounded img-responsive">
                                            <br>
                                            <strong>#3 Input the amount of credit that you want to transfer in the “Credits” field and press the + button
                                            </strong>
                                            <br>
                                            <br>
                                            <br>
                                            <img src="https://netxo.gapi.lol/img/faq/007-3.png" alt="..." class="img-rounded img-responsive">
                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#008">How to create users (players)?</a>
                                        </h4>
                                    </div>
                                    <div id="008" class="panel-collapse collapse">
                                        <div class="panel-body">
                                            <strong>#1 From the left side panel press “Shops”
                                            </strong>
                                            <br>
                                            <br>
                                            <br>
                                            <img src="https://netxo.gapi.lol/img/faq/007-1.png" alt="..." class="img-rounded img-responsive">
                                            <br>
                                            <strong>#2 Choose the shop from the shop list
                                            </strong>
                                            <br>
                                            <br>
                                            <br>
                                            <img src="https://netxo.gapi.lol/img/faq/007-2.png" alt="..." class="img-rounded img-responsive">
                                            <br>
                                            <strong>#3 Click create new user fill in username / name and password and click (Create new user)
                                            </strong>
                                            <br>
                                            <br>
                                            <br>
                                            <img src="https://netxo.gapi.lol/img/faq/008-1.png" alt="..." class="img-rounded img-responsive">
                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#009">How change lobby on shop</a>
                                        </h4>
                                    </div>
                                    <div id="009" class="panel-collapse collapse">
                                        <div class="panel-body">
                                            <strong>#1 From the left side panel press “Shops”
                                            </strong>
                                            <br>
                                            <br>
                                            <br>
                                            <img src="https://netxo.gapi.lol/img/faq/007-1.png" alt="..." class="img-rounded img-responsive">
                                            <br>
                                            <strong>#2 Choose the shop from the shop list
                                            </strong>
                                            <br>
                                            <br>
                                            <br>
                                            <img src="https://netxo.gapi.lol/img/faq/007-2.png" alt="..." class="img-rounded img-responsive">
                                            <br>
                                            <strong>#3 Select witch lobby you like and submit save
                                            </strong>
                                            <br>
                                            <br>
                                            <br>
                                            <img src="https://netxo.gapi.lol/img/faq/009-1.png" alt="..." class="img-rounded img-responsive">
                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#010">How to add bonus 10% to the shop</a>
                                        </h4>
                                    </div>
                                    <div id="010" class="panel-collapse collapse">
                                        <div class="panel-body">
                                            <strong>#1 From the left side panel press “Shops”
                                            </strong>
                                            <br>
                                            <br>
                                            <br>
                                            <img src="https://netxo.gapi.lol/img/faq/007-1.png" alt="..." class="img-rounded img-responsive">
                                            <br>
                                            <strong>#2 Choose the shop from the shop list
                                            </strong>
                                            <br>
                                            <br>
                                            <br>
                                            <img src="https://netxo.gapi.lol/img/faq/007-2.png" alt="..." class="img-rounded img-responsive">
                                            <br>
                                            <strong>#3 Select from drop down menu 10% - 20% and save
                                            </strong>
                                            <br>
                                            <br>
                                            <br>
                                            <img src="https://netxo.gapi.lol/img/faq/010-1.png" alt="..." class="img-rounded img-responsive">
                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#011">How to activate jackpot</a>
                                        </h4>
                                    </div>
                                    <div id="011" class="panel-collapse collapse">
                                        <div class="panel-body">
                                            <strong>#1 From the left side panel press “Shops”
                                            </strong>
                                            <br>
                                            <br>
                                            <br>
                                            <img src="https://netxo.gapi.lol/img/faq/007-1.png" alt="..." class="img-rounded img-responsive">
                                            <br>
                                            <strong>#2 Choose the shop from the shop list
                                            </strong>
                                            <br>
                                            <br>
                                            <br>
                                            <img src="https://netxo.gapi.lol/img/faq/007-2.png" alt="..." class="img-rounded img-responsive">
                                            <br>
                                            <strong>#3 Enable jackpot button and save
                                            </strong>
                                            <br>
                                            <br>
                                            <br>
                                            <img src="https://netxo.gapi.lol/img/faq/0011-1.png" alt="..." class="img-rounded img-responsive">
                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#012">How to change minimum bet and maximum bet</a>
                                        </h4>
                                    </div>
                                    <div id="012" class="panel-collapse collapse">
                                        <div class="panel-body">
                                            <strong>#1 From the left side panel press “Shops”
                                            </strong>
                                            <br>
                                            <br>
                                            <br>
                                            <img src="https://netxo.gapi.lol/img/faq/007-1.png" alt="..." class="img-rounded img-responsive">
                                            <br>
                                            <strong>#2 Choose the shop from the shop list
                                            </strong>
                                            <br>
                                            <br>
                                            <br>
                                            <img src="https://netxo.gapi.lol/img/faq/007-2.png" alt="..." class="img-rounded img-responsive">
                                            <br>
                                            <strong>#3 Click on advanced settings
                                            </strong>
                                            <br>
                                            <br>
                                            <br>
                                            <img src="https://netxo.gapi.lol/img/faq/0012-1.png" alt="..." class="img-rounded img-responsive">
                                            <br>
                                            <strong>#4 Select from dropdown menu minimum bet and maximum bet and save
                                            </strong>
                                            <br>
                                            <br>
                                            <br>
                                            <img src="https://netxo.gapi.lol/img/faq/0012-2.png" alt="..." class="img-rounded img-responsive">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</section>
@stop

@section('scripts')
<script src="https://cdn.rawgit.com/alertifyjs/alertify.js/v1.0.10/dist/js/alertify.js"></script>
<script>

</script>
<style>
.faqHeader {
    font-size: 27px;
    margin: 20px;
}
.font-bold {
    font-weight: 700;
}
</style>
@stop