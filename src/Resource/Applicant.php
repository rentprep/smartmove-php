<?php

namespace SmartMove\Resource;

/**
 * Class Applicant
 *
 * @package SmartMove
 *
 * @property $AccountId
 * @property $FirstName
 * @property $LastName
 * @property $MiddleName
 * @property $EmailAddress
 * @property $EmploymentIncome
 * @property $EmploymentIncomePeriod
 * @property $EmploymentStatus
 * @property $OtherIncome
 * @property $OtherIncomePeriod
 * @property $RequestCreatedDate
 * @property $RequestExpirationDate
 * @property $ApplicantStatus
 * @property $Assets
 * @property $CreditReport
 * @property $CriminalRecords
 * @property $EvictionRecords
 * @property $ExtraElements
 * @property $ParsingErrors
 */
class Applicant extends Resource {

    public function getFullName() {
        return "{$this->FirstName} {$this->LastName}";
    }

}
