<?php

namespace SmartMove\Resource;

use SmartMove\Util;

/**
 * Class Application
 *
 * @package SmartMove
 *
 * @property $ApplicationId
 * @property $LandlordPays
 * @property $PropertyId
 * @property $Rent
 * @property $Deposit
 * @property $LeaseTermInMonths
 * @property $CreditRecommendation
 * @property $CreditRecommendationPolicyText
 * @property $SelectedBundle
 * @property $Status
 * @property $Applicants
 * @property $UnitNumber
 * @property $ProductBundle
 * @property $ExtraElements
 * @property $ParsingErrors
 */
class Application extends Resource {

    public function __construct($data = []) {
        parent::__construct($data);
        $this->Applicants = isset($this->Applicants) ? Util::convertToSmartMoveObject($this->Applicants, Applicant::class) : null;
        $this->Property = isset($this->Property) ? Util::convertToSmartMoveObject($this->Property, Property::class) : null;
    }
}
