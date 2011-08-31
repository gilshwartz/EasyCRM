<?php

class OffersDetail extends StoreAppModel {

    var $name = 'OffersDetail';
    var $virtualFields = array(
        'amount' => 'round(OffersDetail.unit_price * OffersDetail.quantity - (OffersDetail.unit_price * OffersDetail.quantity * OffersDetail.discount /100), 2)'
    );

    var $belongsTo = array(
        'Offer' => array(
            'className' => 'Offer',
            'foreignKey' => 'offer_id'
        )
    );

}

?>