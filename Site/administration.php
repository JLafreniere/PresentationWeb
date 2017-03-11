<?php  
       if (session_status() == PHP_SESSION_NONE) {
            session_start();
            }
if(isset($_SESSION['admin'])){
    if ($_SESSION['admin'] == '0'){
     header('Location: accueil.php');
    }

}else{ header('Location: accueil.php');};

?>
<html ng-app='app_angular' ng-controller="ctrl">
  <head>
    <?php include 'components/headcontent.php';?>
    <title>défi santé - administration</title>
  </head>
  <body class="ng-cloak">
    
    <header>
      <?php include 'components/nav.php';?>
      
    </header>
    <main>

    <script>
      
      
    </script>
    <div class="container">
      <h4>administration</h4>
      <ul class="collapsible" data-collapsible="expandable">
        <li>
          <div class="collapsible-header"><i class="material-icons">supervisor_account</i>groupes <span class="new badge blue right" data-badge-caption="">{{groupes.length}}</span></div>
          <div class="collapsible-body" class="collapsiblewithbutton" style="padding-bottom: 45px !important">
          <div class="center"><h4>groupes</h4></div>
          <div class="container">
            <div input-field class="center">
              <input type="checkbox" checked name="masquergroupes" ng-model="masquergroupes" id="masquergroupes" value="1" class="field green filled-in">  
              <label for="masquergroupes" style="margin-top: 10px" >masquer les groupes dont je ne suis pas responsable</label>
              <br>
              <div class="row">
              <br><br>
              <b class="center">limiter à la session</b>
              <select name="select_session" id="select_session" ng-model="session" ng-change="test()">
                <option value="-1" disabled>veuillez sélectionner une session</option>
                <option value="0" selected>toutes les sessions</option>
                <option ng-repeat="s in sessions" value="{{s.id_session}}">{{s.nom_session}}</option>
              </select>
              <br>
              </div>
         
              
              
              
              </div>
              
          </div>

           

            <ul class="collapsible" data-collapsible="expandable" >
              
              <li ng-repeat="groupe in groupes" ng-show="((groupe.id_prof == <?=$_session['uid']?>) || !masquergroupes) && (groupe.id_session == session || session<=0)"> <!-- angular repeat -->
         

              <span class="hide-on-small-only new badge blue right" style="margin-right: 14px !important" data-badge-caption="">{{elevesdansgroupe(groupe.id_groupe).length}} élève<span ng-show="elevesdansgroupe(groupe.id_groupe).length>1">s</span></span>
              <div class="collapsible-header"><i class="material-icons">supervisor_account</i>{{groupe.nom_groupe}} <span class="hide-on-small-only new badge yellow darken-2 right"  data-badge-caption=""> ens. {{groupe.ensemble}}</span>
                <span class="right">{{groupe.nom_session}}</span>

                <i class="material-icons right" ng-show="!(groupe.id_prof == <?=$_session['uid']?>)" title="vous n'êtes pas responsable de ce groupe">lock</i>
              </div>

              <div class="collapsible-body collapsiblewithbutton"><div class="container">
              <b>session: </b>{{groupe.nom_session}} <br>
              <b>ensemble: </b>{{groupe.ensemble}} <br>
              <b>professeur responsable: </b>{{elevefromid(groupe.id_prof).nom}}, {{elevefromid(groupe.id_prof).prenom}}


              </div>
              <div ng-show="elevesdansgroupe(groupe.id_groupe).length == 0"> 
                <br>  <br>  
                <div class="center">  
                <b>aucun élève inscrit dans ce groupe pour l'instant  </b>
</div>
              </div>

              <div ng-show="elevesdansgroupe(groupe.id_groupe).length > 0"> 
              <br>  <br>  
              <table class="striped" align="center" ng-show="groupe.ensemble == 1"><!--ensemble 1 -->
                  <thead><tr><th>nom </th><th class="center"><span class="hide-on-med-and-up">pts reg.</span><span class="hide-on-small-only">points réguliers</span></th><th class="center"><span class="hide-on-med-and-up">pts bon.</span><span class="hide-on-small-only">points bonus</span></th><th class="center"><span class="hide-on-med-and-up">pen.</span><span class="hide-on-small-only">pénalité</span></th><th class="center">total</th></tr></thead>
                  <tr  ng-repeat="eleve in elevesdansgroupe(groupe.id_groupe)">
                    
                    <td class="">{{eleve.nom}}, {{eleve.prenom}}</td>
                    <td style="text-align: center" class="center">{{pointsreguliersforeleve(eleve.id_utilisateur)}}/5</td><td class="center">{{pointsbonusensemble1foreleve(eleve.id_utilisateur)}}/5</td><td class="center"><span ng-show="penaliteforeleve(eleve.id_utilisateur) > 0" style="color: red">{{penaliteforeleve(eleve.id_utilisateur)}}</span>   </td><td class="center">{{pointsbonusensemble1foreleve(eleve.id_utilisateur)+pointsreguliersforeleve(eleve.id_utilisateur) - penaliteforeleve(eleve.id_utilisateur)}}/10</td>
                    
                  </div>
                </tr>
              </table>

                <table class="striped" align="center" ng-show="groupe.ensemble == 2"><!--ensemble 2 -->
                  <thead><tr><th>nom </th><th class="center">nombre de points</th><th class="center">pénalité</th><th class="center">total</th></tr></thead>
                  <tr  ng-repeat="eleve in elevesdansgroupe(groupe.id_groupe)">
                    
                    <td>{{eleve.nom}}, {{eleve.prenom}}</td><td class="center">{{pointsensemble2(eleve.id_utilisateur)}}/5</td><td class="center"><span ng-show="penaliteforeleve(eleve.id_utilisateur) > 0" style="color: red">{{penaliteforeleve(eleve.id_utilisateur)}}</span>  </td><td class="center">{{pointsensemble2(eleve.id_utilisateur)-penaliteforeleve(eleve.id_utilisateur)}}</td>
                    
                  </div>
                </tr>
              </table>



                <table class="striped" align="center" ng-show="groupe.ensemble == 3"><!--ensemble 3 -->
                  <thead><tr><th>nom </th><th class="center"><span class="hide-on-med-and-up">deb.</span><span class="hide-on-small-only">début</span></th><th class="center">fin</th><th class="center"><span class="hide-on-med-and-up">bon.</span><span class="hide-on-small-only">bonus</span></th><th class="center"><span class="hide-on-med-and-up">pen.</span><span class="hide-on-small-only">pénalité</span></th><th class="center"><span class="hide-on-med-and-up">total</span><span class="hide-on-small-only">total</span></th></tr></thead>
                  <tr  ng-repeat="eleve in elevesdansgroupe(groupe.id_groupe)">
                    
                    <td class="">{{eleve.nom}}, {{eleve.prenom}}</td>
                    <td style="text-align: center" class="center">{{pointsdebutforeleve(eleve.id_utilisateur)}}/5</td><td class="center"> {{pointsfinforeleve(eleve.id_utilisateur)}}/5</td><td class="center"> {{pointsbonusforeleve(eleve.id_utilisateur)}}/5</td>
                    <td class="center"><span ng-show="penaliteforeleve(eleve.id_utilisateur) > 0" style="color: red">{{penaliteforeleve(eleve.id_utilisateur)}}</span>  </td>
                    <td class="center">{{pointsdebutforeleve(eleve.id_utilisateur)+pointsfinforeleve(eleve.id_utilisateur)+pointsbonusforeleve(eleve.id_utilisateur)}}/15</td>
                    
                  </div>
                </tr>
              </table>
              </div>
              <div class="container">
              <div class="row" style="text-align: center">
              
              <button data-target="modalgengroupe" ng-click="setgroupe(groupe.id_groupe)" ng-show="(groupe.id_prof == <?=$_session['uid']?>)" style="margin-bottom: 15px !important; margin-top: 30px !important" class=" green btn" >générer des codes d'accès</button></div>
              <div class="row"  style="text-align: center">
                <button ng-click="setgroupe(groupe.id_groupe)" data-target="modalgroupe" ng-show="(groupe.id_prof == <?=$_session['uid']?>)" style="margin-bottom: 15px !important" class="btn  green modal-trigger">afficher les codes d'accès</button>
              </div>
              <div class="row"  style="text-align: center">
                <button data-target="modalpromotion" onclick="" ng-click="setpromotionid(groupe.id_groupe)" style="margin-bottom: 15px !important" class="btn green modal-trigger">promouvoir</button>
              </div>
              <div class="row"  style="text-align: center">
                <button ng-click="supprimergroupe(groupe.id_groupe, groupe.nom_groupe)" ng-show="(groupe.id_prof == <?=$_session['uid']?>)" style="margin-bottom: 15px !important" class="btn red modal-trigger">supprimer le groupe</button>
              </div>
          </div>
            </div>
            
          </li>
        </ul>
        <br>
        <div class="center">
        <button data-target="modalnouveaugroupe" style="margin-bottom: 0px !important" class="btn green">nouveau groupe</button>
        </div>
        <br>

          <ul class="collapsible" data-collapsible="expandable" >
              
              <li> <!-- sans groupe -->
              <div class="collapsible-header"><i class="material-icons">supervisor_account</i>utilisateurs sans groupes
                
                <span class="new badge blue right hide-on-small-only" data-badge-caption="">{{utilisateurssansgroupes.length}} utilisateur<span ng-show="utilisateurssansgroupes.length>1">s</span></span>
              </div>
              <div class="collapsible-body collapsiblewithbutton">
              
                <div ng-show="utilisateurssansgroupes.length == 0"> 
                <br>  
                <div class="center">  
                <b>aucun élève sans groupe inscrit pour l'instant</b>
                </div>
                </div>


              <table class="striped" align="center" ng-show="utilisateurssansgroupes.length > 0">
              <thead> <th>utilisateurs</th></thead>
                    <tr ng-repeat="eleve in utilisateurssansgroupes"><td> {{eleve.nom}}, {{eleve.prenom}}</td></tr>
              </table>
              <div class="container">

                                 <div class="row" style="text-align: center">
              <button data-target="modalgengroupe0" ng-click="setgroupe(0)" style="margin-bottom: 15px !important; margin-top: 30px !important" class="btn green" >générer des codes d'accès</button></div>
              <div class="row"  style="text-align: center">
                <button data-target="modalgroupe0" style="margin-bottom: 15px !important" class="btn green modal-trigger">afficher les codes d'accès</button>
              </div>
              <div class="row"  style="text-align: center">
                <button data-target="modalpromotion" ng-click="setpromotionid(0)" style="margin-bottom: 15px !important" class="btn green modal-trigger">promouvoir</button>
              </div>

              </div>
</div>
            
               </li>
          </ul>
          <br>
      
      </div>
      
      <li>
        <div class="collapsible-header"><i class="material-icons">directions_bike</i>activités prévues<span class="new badge green right" data-badge-caption="">{{activites_prevues.length}}</span></div>
        
        <div class="collapsible-body">
          <div class="center">
          <h4>activités planifiées</h4>
          </div>
          <div class="container">
          <input type="checkbox" checked name="masquerpasse" ng-model="masquerpasse" id="masquerpasse" value="1" class="filtresactivites field filled-in">  
            <label for="masquerpasse" style="margin-top: 10px" >masquer les activités passées</label>
          <br>

          <input type="checkbox" checked name="masquerpresence" id="masquerpresence" ng-model="masquerpresence" value="1" class="filtresactivites field filled-in">  
            <label for="masquerpresence" style="margin-top: 10px" >masquer les activités où les présences ont été prises</label>


            <br><br>  <br>
          </div>
          <ul ng-show="activites_prevues.length > 0" class="collapsible" data-collapsible="expandable">
            <li ng-repeat="activite in activites_prevues" class="coll_act_prev" ng-show="!(activite.presences_prises > 0 && masquerpresence) && !(todate(activite.date_activite) < now && masquerpasse) ">
            <!-- angular repeat -->
            <div class="collapsible-header">
              <i class="material-icons">directions_bike</i>{{activitefromid(activite.id_activite).nom_activite}} le {{activite.date_activite}} à {{formatheure(activite.heure_debut)}}
                
              <span class=" hide-on-small-only new badge green right" data-badge-caption="">{{getelevesforactiviteprevue(activite.id_activite_prevue).length}}/{{activite.participants_max}}</span>
              <i class=" hide-on-small-only material-icons right" ng-show="activite.presences_prises > 0">playlist_add_check</i>
              <i class=" hide-on-small-only material-icons right" style="pointer-events: visiblepainted !important;" ng-click="show_params(activite)">settings</i>

              
            </div>
            <div class="collapsible-body collapsiblewithbutton ">
              <div class="center" >
              
              <i class=" hide-on-med-and-up material-icons " ng-show="activite.presences_prises > 0" >playlist_add_check</i><br>
              <br> <br>  
              <br>
            </div>
            <div class="container">
              <table>
                <b>responsable: </b>{{elevefromid(activite.responsable).nom}}, {{elevefromid(activite.responsable).prenom}}
                <br>  
                <b>frais: </b> {{activite.frais}}$ <br>  
                <b>pondération: </b> {{activitefromid(activite.id_activite).ponderation}} point<span ng-show="activitefromid(activite.id_activite).ponderation > 1">s</span><br> 
                <b>endroit: </b> {{activite.endroit}} <br>  
                <b>commentaire: </b>{{activitefromid(activite.id_activite).commentaire}} <br> 
                <b>nombre de participants inscrits: </b>{{getelevesforactiviteprevue(activite.id_activite_prevue).length}}/{{activite.participants_max}}
                  <br>
                  
                  <br>  
                    <h5>liste de présences <span style="color: green; font-size: 0.75em" ng-show="activite.presences_prises > 0"> - présences prises</span></h5> 
                    <span ng-show="getelevesforactiviteprevue(activite.id_activite_prevue).length == 0">aucune inscription pour le moment <br><br></span>
                 <table ng-show="getelevesforactiviteprevue(activite.id_activite_prevue).length >= 1"> 
                 <thead><th>nom</th><th class="center">présent</th>  </thead>
                <tr  ng-repeat="eleve in getelevesforactiviteprevue(activite.id_activite_prevue)">
                  <td class="col s8">{{eleve.nom}}, {{eleve.prenom}}</td><td class="col s2 center">
                  
                  <input class="field filled-in" ng-checked="{{getpresenceforeleve(activite.id_activite_prevue, eleve.id_utilisateur)}}" type="checkbox" name="viewid{{activite.id_activite_prevue}}" value="{{eleve.id_utilisateur}}" disabled readonly
                  id="viewid{{activite.id_activite_prevue}}-{{eleve.id_utilisateur}}" style="margin-right: 15px; margin-top: 15px">
                  <label for="viewid{{activite.id_activite_prevue}}-{{eleve.id_utilisateur}}" style="margin-top: 10px" ></label>
                  
                  
                </td>
              </tr>
               </table>   
              
            </table>
</div>
            <div style="text-align: center">
              
              <button type="button" class="btn green waves-effect waves-light" style="height: 30px !important" ng-click="show_params(activite)">modifier</button><br>
                <button type="button" ng-click="setactselectionne(activite.id_activite_prevue)" data-target="modalpresence" class="btn green waves-effect waves-light " style="height: 30px;"><span ng-show="activite.presences_prises > 0">re</span>prendre les présences</button><br>
                <button ng-click="annuleractivite(activite.id_activite_prevue)" type="button" class="btn red waves-effect waves-light " style="height: 30px;">annuler l'activité</button>
              
            </div>
          </div>
          
        </li>
      </ul>
    <div class="center">
    <br>
      <a class="waves-effect green waves-light btn" data-target="modal_planif" style="margin-top: 0px;">planifier une activité</a></div>
      <br>
      </div></li>
    

  <!-- administration -->


 
      <li>
        <div class="collapsible-header"><i class="material-icons">settings</i>paramètres administratifs</div>
        
        <div class="collapsible-body">
          
          <ul class="collapsible" data-collapsible="expandable">
            
            
            <li> <!-- angular repeat -->
            <div class="collapsible-header">
              <i class="material-icons">supervisor_account</i>
              comptes administrateurs
                <span class="new badge blue right" data-badge-caption="">{{comptesadmin().length}}</span>
            </div>
            <div class="collapsible-body collapsiblewithbutton container">
              
              <table class="striped">
              <thead><th class="center">compte</th><th class="center">niveau</th></thead>
            
              <tr ng-repeat="admin in comptesadmin()"><td>{{admin.nom}}, {{admin.prenom}}</td><td class="center">{{adminlevelfromid(admin.administrateur)}}</td><td class="center"><button type="button" class="hide-on-small-only btn green small" ng-click="ouvrirmodalmodifierpermission(admin.id_utilisateur, admin.administrateur)" style="margin-top: 0px !important"><span class="">permissions</span></button><i ng-click="ouvrirmodalmodifierpermission(admin.id_utilisateur, admin.administrateur)" class="hide-on-med-and-up material-icons">settings</i></td></tr>
                </table>
                <div class="center">
              <div class="row center">
              <a class="waves-effect waves-light btn green" style="margin-top: 15px;" data-target="modalcodeadmin">générer des codes d'accès</a></div>
              <div class="row">
            <a class="waves-effect waves-light btn green" style="margin-top: 15px;" type="button" data-target="modalaffichercodeadmin" onclick="$('#modalaffichercodeadmin').modal('open')">afficher les codes d'accès</a></div>


              
              
              </div>
             </div>
            </li>

            <li> <!-- angular repeat -->
            <div class="collapsible-header">
              <i class="material-icons">explore</i>
              activités
                <span class="new badge green right" data-badge-caption="">{{activites.length}}</span>
            </div>
            <div class="collapsible-body collapsiblewithbutton container">
              
              
                <table class="striped">
                <thead>
                  <th class="center">activités disponibles</th><th class="center hide-on-med-and-down">durée (minutes)</th><th class="center hide-on-med-and-down">pondération</th><th></th>
                </thead>                
                <tr ng-repeat="activite in activites">
                  <td class="center">{{activite.nom_activite}}</td>
                  <td class="center hide-on-med-and-down">{{activite.duree}}</td>
                  <td class="center hide-on-med-and-down">{{activite.ponderation}} point<span ng-show="activite.ponderation>1">s</span></td> 
                  <td class="center"><a class="btn-floating  waves-effect waves-light green" ng-click="modifieractivite(activite)"><i class="material-icons">edit</i></a></td>
                  <td class="center"><a class="btn-floating  waves-effect waves-light red" ng-click="supprimeractivite(activite.id_activite)"><i class="material-icons">delete</i></a></td>
                </tr>
  

                </table>




              
              
              <row>
              <br><br>
              <div style="text-align: center">
              <button type="button"  class="btn l5 waves-effect waves-light green"  data-target="modal_new_activite" style="height: 30px; margin-top: 7px; margin-right: 7px">ajouter une activité</button>
              </div>
              </row>
              
             </div>
            </li>


            <li> <!-- sessions repeat -->
            <div class="collapsible-header">
              <i class="material-icons">date_range</i>
              sessions
                
            </div>
            <div class="collapsible-body collapsiblewithbutton container">
              
              <table class="striped"><thead><th>nom de la session</th><th class="hide-on-med-and-down">début</th>
              <th class="hide-on-med-and-down">mi-session</th>
              <th class="hide-on-med-and-down">fin</th><th></th></thead>
              <tr ng-repeat="session in sessions"><td>{{session.nom_session}}</td><td class="hide-on-med-and-down">{{session.debut_session}}</td><td class="hide-on-med-and-down">{{session.mi_session}}</td><td class="hide-on-med-and-down">{{session.fin_session}}</td><td><a class="btn-floating waves-effect waves-light green " ng-click="modifiersession(session)"><i class="material-icons">edit</i></a></td><td><a class="btn-floating waves-effect waves-light blue " ng-click="afficherstats(session.id_session)"><i class="material-icons">assessment</i></a></td>
              </tr>
              </table>
              <br>
              
              <div class="center">
              <button type="button"  class="green btn l6 s12 waves-effect waves-light " data-target="modal_session" style="height: 30px; margin-top: 7px; margin-right: 7px">ajouter une session</button>
              </div>

              </row>
             </div>
            </li>



      </ul>
      </div>

      </li>    
  </ul>
  
</div>


<div id="modal_mod_planif" class="modal">
      <div class="modal-content">
      <input type="hidden" id="id_act_plan">
       <div class="row" style="text-align:center">
           <h4>modifier une activité</h4>
       </div>
        <div class="row">
         <div class="input-field col s12">
           <select required id="mod_nom_act" name="nom_act">
           <option value="">choisir une activité *</option>
           <option ng-repeat="activite in activites" value={{activite.id_activite}}>{{activite.nom_activite}}, {{activite.duree}} minutes</option>
           </select>
           <label class="activer" for="mod_nom_act">nom de l'activité *</label> 
         </div>
         </div>

          <div class="row">
          <div class="input-field col s12">
           <input id="mod_date_act" type="date" class="datepicker">
           <label  class="activer" for="mod_date_act">date de l'activité *</label>
         </div>
         </div>
 
         <div class="row">
           <div class="input-field col s12">
             <label class="activer" for="mod_heure_deb">heure de début*</label>
             <input id="mod_heure_deb" class="timepicker" type="time" ng-model="$ctrl.na">
           </div>
         </div>
 
 
       <div class="row">
         <div class="input-field col s6 l6">
           <label   class="activer" for="mod_participants_max">nombre de participants maximum</label>
           <input type="number" step="1"  min="0" max="180" id="mod_participants_max" name="participants_max"/>
         </div>
 
         <div class="input-field col s6 l6">
           <label class="activer"  for="mod_frais">frais de l'activité</label>
           <input type="number" step="5" min="0" id="mod_frais" name="frais"/>
         </div>
       </div>
 
       <div class="row">
         <div class="input-field col s12 l12">
           <input type="text" id="mod_endroit" class="materialize-textarea"></textarea>
           <label class="activer" for="mod_endroit">endroit</label>
         </div>
       </div>

        <div class="row" id="">
         <div class="input-field col s12 l12">
              <select id="mod_responsable" name="mod_responsable">
              <option value="null">choisir un responsable</option>
              <option ng-repeat="admin in comptesadministrateur" value="{{admin.id_utilisateur}}">{{admin.nom}}, {{admin.prenom}}</option>
            </select>
         </div>
       </div>
      
        <div class="row">
         <div class="col s12 l12">
           <button type="button" class="btn green" href="" style="width:100%" ng-click="modifieractiviteprevue()">enregistrer</button>
         </div>
         <div class="col s12 l12" style="height: 15px;"></div>
         <div class="col s12 l12">
           <button class="btn red"  style="width: 100%" onclick="$('.modal').modal('close');">annuler</button>
         </div>
     </div>  
 
   </div>
  </div>
 </div>














</div>



</main>
<footer class="page-footer" style="width: 100%!important">

</footer>

<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.1/angular.min.js"></script>
<script type="text/javascript" src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
<script src="js/moment.js">moment.locale="fr"</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.1/locale/fr-ca.js"></script>
<script type="text/javascript" src="js/materialize.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.1.0/fullcalendar.min.js"></script>
<script type="text/javascript" src="js/fullcalendar-fr.js"></script>
<script type="text/javascript" src="js/gcal.js"></script>
<script type="text/javascript" src="js/sc-date-time.js"></script>
<script src="js/scripts.js"></script>
<script src="https://cdn.rawgit.com/chingyawhao/materialize-clockpicker/master/dist/js/materialize.clockpicker.js"></script>

<?php include 'js/scriptsadmin.php';

include 'components/modals_admin.php';
?>








<script>

$(document).ready(function(){
// the "href" attribute of .modal-trigger must specify the modal id that wants to be triggered
$('.modal').modal();
$('.timepicker').pickatime({
    default: 'now',
    twelvehour: false, // change to 12 hour am/pm clock from 24 hour
    donetext: 'ok',
  autoclose: false,
  vibrate: true // vibrate the device when dragging clock hand
});
 

  $(document).ready(function(){
  $('.slider').slider();
 });
 
$('#date_act').pickadate();
$('#mod_date_act').pickadate();
$('input[type="date"]').pickadate();

$("select").material_select();

$(".session:last").attr("checked", true);
    $('#select_session').val(0);    
   $('#select_session').material_select();   

});


 



</script>
<div class="hiddendiv common"></div>

</body></html>
