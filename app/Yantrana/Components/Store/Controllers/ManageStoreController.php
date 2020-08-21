<?php
/*
* ManageStoreController.php - Controller file
*
* This file is part of the Store component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Store\Controllers;

use App\Yantrana\Core\BaseController;
use App\Yantrana\Components\Store\ManageStoreEngine;
use App\Yantrana\Components\Store\Requests\EditStoreSettingsRequest;

class ManageStoreController extends BaseController
{
    /**
     * @var ManageStoreEngine - ManageStore Engine
     */
    protected $manageStoreEngine;

    /**
     * Constructor.
     *
     * @param ManageStoreEngine $manageStoreEngine - ManageStore Engine
     *-----------------------------------------------------------------------*/
    public function __construct(ManageStoreEngine $manageStoreEngine)
    {
        $this->manageStoreEngine = $manageStoreEngine;
    }

    /**
     * Get settings edit support data.
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function settingsEditSupportData($formType)
    {  
        $processReaction = $this->manageStoreEngine
                                ->prepareSettingsEditSupportData($formType);

        return __processResponse($processReaction, [
            ], $processReaction['data']);
    }

    /**
     * Handle edit store settings request.
     *
     * @param object EditStoreSettingsRequest $request
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function editSettings(EditStoreSettingsRequest $request, $formType)
    {
        $processReaction = $this->manageStoreEngine
                                ->processEditStoreSettings($request->all(), $formType);

        return __processResponse($processReaction, [
                   1 => __tr('Settings updated successfully.'),
                3 => __tr('Please select the logo.'),
                4 => __tr('File has an invalid extension, it should be png.'),
                14 => __tr('Settings not updated.'),
             ], $processReaction['data']);
    }

    /**
     * Handle support data.
     *
     * @param string $formType
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function getEmailTemplatetData()
    {
        $processReaction = $this->manageStoreEngine
                                ->prepareEmailTemplateSupportData();

        return __processResponse($processReaction, [], $processReaction['data']);
    }

    /**
     * Handle support data.
     *
     * @param string $formType
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function getEditLandingPageData()
    {   
        $processReaction = $this->manageStoreEngine
                                ->prepareEditLandingPageData();

        return __processResponse($processReaction, [], $processReaction['data']);
    }

    /**
     * Handle edit config request.
     *
     * @param string $formType
     * @param object EditStoreSettingsRequest $request
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function landingPageUpdate(EditStoreSettingsRequest $request)
    {     
        $processReaction = $this->manageStoreEngine
                                ->processLandingPageEditOrStore($request->all());
                            

        return __processResponse($processReaction, [], $processReaction['data']);
    }

    /**
     * Handle support data.
     *
     * @param string $formType
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function getEditFooterTemplatetData()
    {   
        $processReaction = $this->manageStoreEngine
                                ->prepareEditFooterTemplateData();

        return __processResponse($processReaction, [], $processReaction['data']);
    }

    /**
     * Handle edit config request.
     *
     * @param string $formType
     * @param object EditStoreSettingsRequest $request
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function footerTemplateEdit(EditStoreSettingsRequest $request, $footerTemplateId)
    {     
        $processReaction = $this->manageStoreEngine
                                ->processFooterTemplateEditOrStore($request->all(), $footerTemplateId);

        return __processResponse($processReaction, []);
    }

     /**
     * Handle edit config request.
     *
     * @param string $formType
     * @param object ConfigurationRequest $request
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function footerTemplateDelete($footerTemplateId)
    {   
        $processReaction = $this->manageStoreEngine
                                ->processEmailTemplateDelete($footerTemplateId);

        return __processResponse($processReaction, []);
    }

    /**
     * Handle support data.
     *
     * @param string $formType
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function getEditEmailTemplatetData($templateId)
    {	
        $processReaction = $this->manageStoreEngine
                                ->prepareEditTemplateSupportData($templateId);

        return __processResponse($processReaction, [], $processReaction['data']);
    }

     /**
     * Handle edit config request.
     *
     * @param string $emaiSubjectId
     * @param object ConfigurationRequest $emaiSubjectId
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function emailTemplateSubjectDelete($emaiSubjectId)
    {	
        $processReaction = $this->manageStoreEngine
                                ->processEmailSubjectDelete($emaiSubjectId);

        return __processResponse($processReaction, []);
    }

    /**
     * Handle edit config request.
     *
     * @param string $formType
     * @param object EditStoreSettingsRequest $request
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function emailTemplateEdit(EditStoreSettingsRequest $request, $emailTemplateId)
    {	
        $processReaction = $this->manageStoreEngine
                                ->processEmailTemplateEditOrStore($request->all(), $emailTemplateId);

        return __processResponse($processReaction, []);
    }

    /**
     * Handle edit config request.
     *
     * @param string $formType
     * @param object ConfigurationRequest $request
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function emailTemplateDelete($emailTemplateId)
    {	
        $processReaction = $this->manageStoreEngine
                                ->processEmailTemplateDelete($emailTemplateId);

        return __processResponse($processReaction, []);
    }

    /**
    * Request to engine for slider data
    *
    * @return  json object
    *---------------------------------------------------------------- */

    public function prepareSliderList() 
    {        
        $processReaction = $this->manageStoreEngine->prepareSliderList();

        return __processResponse($processReaction, [], [], true);
    }
    

    /**
      * Slider create process 
      *
      * @param  object SliderAddRequest $request
      *
      * @return  json object
      *---------------------------------------------------------------- */

    public function processSliderCreate(EditStoreSettingsRequest $request)
    {   
        $processReaction = $this->manageStoreEngine
                                ->processSliderCreate($request->all());

        return __processResponse($processReaction, [], [], true);
    }

    /**
      * Slider get update data 
      *
      * @param  mix $sliderId
      *
      * @return  json object
      *---------------------------------------------------------------- */

    public function updateSliderData($sliderID)
    {   
        $processReaction = $this->manageStoreEngine
                            ->prepareSliderUpdateData($sliderID);

        return __processResponse($processReaction, [], [], true);
    }

    /**
      * Slider process update 
      * 
      * @param  mix @param  mix $sliderId
      * @param  object SliderEditRequest $request
      *
      * @return  json object
      *---------------------------------------------------------------- */

    public function updateSlider($sliderID, EditStoreSettingsRequest $request)
    {   
        $processReaction = $this->manageStoreEngine
                                ->processSliderUpdate($sliderID, $request->all());

        return __processResponse($processReaction, [], [], true);
    }

    /**
    * Request for delete Specification 
    *
    * @param object $specificationId
    *
    * @return  json object
    *---------------------------------------------------------------- */

    public function deleteSlider($sliderID, $title) 
    {         
        $processReaction = $this->manageStoreEngine->deleteSliderData($sliderID, $title);

        return __processResponse($processReaction, [
                    1 => __tr('Slider deleted successfully.'),
                    2 => __tr('Something went wrong.'),
                    18 => __tr('Slider does not exist.'),
                ]);
    }


    /**
    * Read slider media
    *
    * @param  object sliderId $sliderId
    *
    * @return  json object
    *---------------------------------------------------------------- */

    public function readSliderMedia($sliderId)
    {   
        $processReaction = $this->manageStoreEngine
                                ->prepareSliderMedia($sliderId);

        return __processResponse($processReaction);
    }
}