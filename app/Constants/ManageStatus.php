<?php

namespace App\Constants;

class ManageStatus
{
    const ACTIVE   = 1;
    const INACTIVE = 0;

    const COW  = 1;
    const GOAT = 2;

    const YES = 1;
    const NO  = 0;

    const UNVERIFIED = 0;
    const VERIFIED   = 1;
    const PENDING    = 2;

    const PAYMENT_INITIATE = 0;
    const PAYMENT_SUCCESS  = 1;
    const PAYMENT_PENDING  = 2;
    const PAYMENT_CANCEL   = 3;

    const CATTLE_ACTIVE     = 1;
    const CATTLE_BOOKED     = 2;
    const CATTLE_DELIVERED  = 3;
    const CATTLE_DIE        = 4;

    const FOOD        = 1;
    const CATTLE      = 2;
    const GEN_EXPENSE = 3;
    const MEDICINE    = 4;

    const CAMPAIGN_REJECTED = 0;
    const CAMPAIGN_APPROVED = 1;
    const CAMPAIGN_PENDING  = 2;

    const CAMPAIGN_COMMENT_REJECTED = 0;
    const CAMPAIGN_COMMENT_APPROVED = 1;
    const CAMPAIGN_COMMENT_PENDING  = 2;


    const CATEGORY_ACTIVE = 1;
    const CATEGORY_INACTIVE = 0;

    const DELIVERED = 1;
    const NOT_DELIVERED = 0;

    const BOOKING_PENDING = 1;
    const BOOKING_DELIVERED  = 2;
    const BOOKING_CHALLAN_PRINT   = 3;
    const BOOKING_CANCELED   = 4;

    
    const CATTLE_CATEGORY_COW_GROUP = 1;
    const CATTLE_CATEGORY_GOAT_GROUP = 2;


    const PURCHASE_CATTLE= 1;
    const BORN_CATTLE= 2;

    const BOOKING_INST= 1;
    const BOOKING_EID= 2;



    const ANONYMOUS_DONOR = 0;
    const KNOWN_DONOR     = 1;
}
