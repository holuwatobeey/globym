<div class="lw-dialog">
    <!-- main heading -->
    <div class="lw-section-heading-block">
        <h3 class="lw-header"><?=  __tr( 'Translate / Transliterate' )  ?></h3>
    </div>
    <!-- /main heading -->

    <!-- form action -->
    <form class="lw-form lw-ng-form" 
        name="TransliterateDialogCtrl.[[ TransliterateDialogCtrl.ngFormName ]]" 
        novalidate>

        <!-- String to be translated -->
        <div class="card">
            <div class="card-body">
                <h5 ng-bind-html="TransliterateDialogCtrl.entityString"></h5>
            </div>
        </div>
        <!-- /String to be translated -->

        <!-- Auto Transliterate -->
        <a href ng-if="TransliterateDialogCtrl.showAutoTransliterate" ng-click="TransliterateDialogCtrl.getAutoTransliterate(TransliterateDialogCtrl.entityString, TransliterateDialogCtrl.transliterateData.language, TransliterateDialogCtrl.inputType)" class="ml-2 mt-2 float-right" title="<?= __tr('Auto Transliterate') ?>"><i class="fa fa-exchange"></i> <?= __tr('Auto Transliterate') ?></a>     
        <!-- Auto Transliterate -->

        <!-- Auto Translate -->
        <a href ng-if="TransliterateDialogCtrl.showTranslateLink" ng-click="TransliterateDialogCtrl.getAutoTranslate(TransliterateDialogCtrl.entityString, TransliterateDialogCtrl.transliterateData.language, TransliterateDialogCtrl.inputType)" class="mt-2 float-right" title="<?= __tr('Auto Translate') ?>"><i class="fa fa-language"></i> <?= __tr('Auto Translate') ?> <span ng-if="TransliterateDialogCtrl.showAutoTransliterate"> |</span></a>     
        <!-- Auto Translate -->

        <!-- Select Language -->
        <lw-form-field field-for="language" label="<?= __tr( 'Select Language' ) ?>" class="mt-3"> 
           <select class="form-control" 
                name="language" ng-model="TransliterateDialogCtrl.transliterateData.language" ng-options="locale.id as locale.name for locale in TransliterateDialogCtrl.availableLocale" ng-change="TransliterateDialogCtrl.getTranslateData(TransliterateDialogCtrl.transliterateData.language)" ng-required="true">
                <option value='' disabled selected><?=  __tr('-- Select Language --')  ?></option>
            </select> 
        </lw-form-field>
        <!-- Select Language -->

        <div class="alert alert-warning mt-5" role="alert" ng-if="TransliterateDialogCtrl.showError">
            <h6 class="alert-heading"><?= __tr('Translation Failed') ?> :</h6>
            <p>
                <?= __tr('As google started paid translation service, this method has a limitation, may your ip get blocked for a particular time.') ?>
            </p>
        </div>

        <!-- translate input Box -->
        <lw-form-field field-for="translate_text" label="<?= __tr( 'Translation' ) ?>" ng-if="TransliterateDialogCtrl.inputType == 1"> 
            <input type="text" 
              class="lw-form-field form-control lw-translate-text"
              name="translate_text"
              ng-required="true" 
              ng-model="TransliterateDialogCtrl.transliterateData.translate_text" />
        </lw-form-field>
        <!-- /translate input Box -->

        <!-- translate textarea -->
        <lw-form-field field-for="translate_text" label="<?= __tr('Translation') ?>" ng-if="TransliterateDialogCtrl.inputType == 2"> 
            <textarea name="translate_text" class="lw-form-field form-control lw-translate-text" ng-required="true" 
            cols="10" rows="3" ng-model="TransliterateDialogCtrl.transliterateData.translate_text"></textarea>
        </lw-form-field><br>
        <!-- /translate textarea -->

        <!-- translate ck-editor -->
        <lw-form-field field-for="translate_text" label="<?= __tr('Translation') ?>" ng-if="TransliterateDialogCtrl.inputType == 3"> 
            <textarea name="translate_text" class="lw-form-field form-control lw-translate-text" ng-required="true" 
            cols="10" rows="3" lw-ck-editor ng-model="TransliterateDialogCtrl.transliterateData.translate_text"></textarea>
        </lw-form-field><br>
        <!-- /translate ck-editor -->

        <div class="modal-footer">
            <!-- update button -->
            <button type="submit" ng-click="TransliterateDialogCtrl.update()" class="lw-btn btn btn-primary lw-btn-process" title="<?= __tr('Update') ?>">
            <?= __tr('Update') ?></button>
            <!-- /update button -->

            <!-- cancel button -->
            <button type="button" ng-click="TransliterateDialogCtrl.closeDialog()" class="lw-btn btn btn-light" title="<?= __tr('Cancel') ?>"><?= ( __tr('Cancel') ) ?></button>
            <!-- /cancel button -->
        </div>
    </form>
</div>