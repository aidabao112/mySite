<?php
    $view->extend('::base.html.php');
    $view['slots']->set('title', 'Contact - Johann SERVOIRE');
?>
<div id="contactJumbotron" class="jumbotron col-md-9 center-block" ng-controller="contactCtrl">
    <div id="alertResult" style="display: none;" class="alert" role="alert">
        <p>{{msgResult}}</p>
    </div>
    <form class="form-horizontal" action="#">
        <fieldset>
            <legend><?php echo $view['translator']->trans('contact_title') ?></legend>
            <div id="formGrpMail" class="form-group">
                <label for="inputEmail" class="col-sm-4 control-label"><?php echo $view['translator']->trans('contact_mail') ?> :</label>
                <div class="col-sm-7">
                    <input type="email" ng-model="email" class="form-control" id="inputEmail" placeholder="Email" />
                </div>
            </div>
            <div id="formGrpObjet" class="form-group">
                <label for="inputObjet" class="col-sm-4 control-label"><?php echo $view['translator']->trans('contact_obj') ?> :</label>
                <div class="col-sm-7">
                    <input type="text" ng-model="objet" class="form-control" id="inputObjet" placeholder="Objet" />
                </div>
            </div>
            <div id="formGrpMsg" class="form-group">
                <label for="textAreaMessage" class="col-sm-4 control-label">Message :</label>
                <div class="col-sm-7">
                    <textarea id="textAreaMessage" ng-model="message" class="form-control"></textarea>
                </div>
            </div>
            <div id="formGrpCaptcha" class="form-group">
                <label for="inputCaptcha" class="col-sm-4 control-label">
                    <span><?php echo $view['translator']->trans('contact_calc') ?> : </span><span>{{fNum}}</span><span>{{opCap}}</span><span>{{sNum}}</span>
                </label>
                <div class="col-sm-3">
                    <input type="text" class="form-control" id="inputCaptcha" style="display: inline;width: 80px;" ng-model="answerCaptcha" />
                    <button type="button" class="btn btn-default" ng-click="generateCaptcha()">
                        <span class="glyphicon glyphicon-refresh"></span>
                    </button>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label"></label>
                <div class="col-sm-7">
                    <button id="btnSend" type="submit" class="btn btn-primary" ng-click="submitForm($event);">
                        <?php echo $view['translator']->trans('contact_send') ?>
                    </button>
                    <i id="loader" style="display:none;" class="fa fa-spinner fa-spin"></i>
                </div>
            </div>
        </fieldset>
    </form>
</div>