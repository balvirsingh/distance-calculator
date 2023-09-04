<?php

namespace App\Service;

use App\Model\Address;

class AddressService
{
    /**
     * @return array
     */
    public static function getAddresses(): array
    {
        return [
              new Address("Eastern Enterprise B.V.", "Deldenerstraat 70, 7551AH Hengelo, The Netherlands"),
              new Address("Eastern Enterprise", "46/1 Office no 1 Ground Floor , Dada House , Inside dada silk mills compound, Udhana Main Rd, near Chhaydo Hospital, Surat, 394210, India"),
              new Address("Adchieve Rotterdam", "Weena 505, 3013 AL Rotterdam, The Netherlands"),
              new Address("Sherlock Holmes", "221B Baker St., London, United Kingdom"),
              new Address("The White House", "1600 Pennsylvania Avenue, Washington, D.C., USA"),
              new Address("The Empire State Building", "350 Fifth Avenue, New York City, NY 10118"),
              new Address("The Pope", "Saint Martha House, 00120 Citta del Vaticano, Vatican City"),
              new Address("Neverland", "5225 Figueroa Mountain Road, Los Olivos, Calif. 93441, USA")
        ];
    }
}