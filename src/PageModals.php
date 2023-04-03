<?php

/**
 * Page Elements for printing
 * 
 * @see       https://github.com/doomiie/gps/
 *
 *
 * @author    Jerzy Zientkowski <jerzy@zientkowski.pl>
 * @copyright 2020 - 2023 Jerzy Zientkowski
 * @license   FIXME need to have a licence
 * @note      This program is distributed in the hope that it will be useful - WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
 * FITNESS FOR A PARTICULAR PURPOSE.
 */

namespace qrcodeslibrary;

class PageModals
{
    public function testModal($id)
    {
        return sprintf('<div class="modal fade" id="%s" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Default Bootstrap Modal</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">...</div>
                <div class="modal-footer"><button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Close</button><button class="btn btn-primary" type="button">Save changes</button></div>
            </div>
        </div>
    </div>
    <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#%s">TEST</button>

    ', $id, $id);
    
    }

    public function modal1015ZlecenieWynikButton(\qrcodeslibrary\ObjectZlecenie $object, $field)
    {
        $identif = $object->id . "_" . $field;
        return sprintf('<div class="modal fade" id="modal_%s" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Wpisz wyniki badania dla %s</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                <form name="wyniki_form%s" id="wyniki_form%s">
                <label class="text-primary text-uppercase  mb-1 " for="">Wynik badania*</label>
                <input type="text" data-store=true class="text-right form-control text-primary mb-3" id="wynik" "_%s" name="wynik_%s" value="A" placeholder="Wpisz wynik badania" required>Wpisz wynik</input>
                <label class="text-primary text-uppercase  mb-1 " for="">Numer protokołu</label>
                <input type="text" data-store=true class="text-right form-control text-primary mb-3" disabled value="%s"></input>
                <label class="text-primary text-uppercase  mb-1 " for="">Typ / Wielkość niezgodności</label>
                <input type="text" data-store=true class="text-right form-control text-primary mb-3" id="typWielkoscNiezgodnosci" "_%s" name="typWielkoscNiezgodnosci_%s" value="Brak" placeholder="Wpisz typ i wielkość niezgodności" required>Wpisz wynik</input>
                <input type="text"  class="d-none" id="elementId" "_%s" name="index" "_%s" disabled value="%s"></input>
                <input type="text"  class="d-none" id="typ" "_%s" name="field" "_%s" disabled value="%s"></input>
                </form>
                
                
                
                
                </div>
                <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-bs-dismiss="modal" id="dismiss_%s">Zamknij bez zapisywania</button>
                <button class="btn btn-primary" form="wyniki_form%s" data-bs-dismiss="modal">Zapisz wyniki</button>
                
                </div>
            </div>
        </div>
    </div>
    <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#modal_%s">WYNIKI</button>

    ', $identif,$identif,$identif,$identif,$identif, $identif,$object->numerZlecenia,$identif,$identif,
    $identif, $identif,$object->id, 
    $field, $identif,$field,
    $identif,$identif,$identif);

    }

    public function scriptReactToForm()
    {

    }

} // koniec klasy!