<script>
    var app = angular.module("app_angular", []);
    app.controller("ctrl", function($scope) {


        $scope.eleves = <?php echo phpselectquery('select id_utilisateur, nom, prenom, id_groupe,code_acces, actif, courriel, telephone, sexe, username, password, administrateur from utilisateurs order by nom asc')?>;

        $scope.groupes = <?php echo phpselectquery('select id_groupe, nom_groupe, id_prof, ensemble, nom_session, groupes.id_session, sessions.nom_session from groupes, sessions where groupes.id_session = sessions.id_session order by sessions.debut_session,  groupes.nom_groupe asc')?>;

        $scope.activites = <?php echo phpselectquery('select * from activites where hidden=false or hidden is null')?>;

        $scope.activites_prevues = <?php echo phpselectquery('select * from activites_prevues where hidden=false or hidden is null order by presences_prises, date_activite ')?>;

        $scope.eleves_activites = <?php echo phpselectquery('select * from utilisateur_activites')?>;

        $scope.sessions = <?php echo phpselectquery('select * from sessions order by debut_session asc')?>;

        $scope.codesadmin = <?php echo phpselectquery('select * from utilisateurs where administrateur >= 1 and not code_acces="" order by administrateur')?>;

        $scope.ensembles = [1, 2, 3];

        $scope.utilisateurssansgroupes = <?php echo phpselectquery('select * from utilisateurs where (id_groupe is null or id_groupe = 0) and  code_acces="" order by nom asc')?>;

        $scope.comptesadministrateur = <?php echo phpselectquery('select * from utilisateurs where administrateur >= 1 and code_acces="" order by nom asc')?>;

        $scope.points_debut = <?php echo phpselectquery('select sum(ponderation) as points_debut, utilisateurs.id_utilisateur
            from utilisateurs, activites, activites_prevues, utilisateur_activites, sessions, groupes 
            where activites_prevues.id_activite = activites.id_activite 
            and activites_prevues.presences_prises = 1
            and utilisateur_activites.id_activite_prevue = activites_prevues.id_activite_prevue 
            and utilisateur_activites.id_utilisateur = utilisateurs.id_utilisateur
            and utilisateurs.id_groupe = groupes.id_groupe
            and groupes.id_session = sessions.id_session
            and activites_prevues.date_activite > sessions.debut_session
            and activites_prevues.date_activite < sessions.mi_session
            and utilisateur_activites.present = 1
            group by utilisateurs.id_utilisateur')?>;

        $scope.points_fin = <?php echo phpselectquery('select sum(ponderation) as points_fin, utilisateurs.id_utilisateur
         from utilisateurs, activites, activites_prevues, utilisateur_activites, sessions, groupes 
            where activites_prevues.id_activite = activites.id_activite 
            and activites_prevues.presences_prises = 1
            and utilisateur_activites.id_activite_prevue = activites_prevues.id_activite_prevue 
            and utilisateur_activites.id_utilisateur = utilisateurs.id_utilisateur
            and utilisateurs.id_groupe = groupes.id_groupe
            and groupes.id_session = sessions.id_session
            and activites_prevues.date_activite > sessions.mi_session
            and activites_prevues.date_activite < sessions.fin_session
            and utilisateur_activites.present = 1
            group by utilisateurs.id_utilisateur')?>

        $scope.penalites = <?php echo phpselectquery('select sum(ponderation) as penalite, utilisateurs.id_utilisateur from utilisateurs, activites, activites_prevues, utilisateur_activites, sessions, groupes where activites_prevues.id_activite = activites.id_activite and utilisateur_activites.id_activite_prevue = activites_prevues.id_activite_prevue and utilisateur_activites.id_utilisateur = utilisateurs.id_utilisateur and utilisateurs.id_groupe = groupes.id_groupe and groupes.id_session = sessions.id_session and activites_prevues.date_activite > sessions.debut_session and activites_prevues.date_activite < sessions.fin_session and utilisateur_activites.present = 0 and activites_prevues.presences_prises = 1 group by utilisateurs.id_utilisateur')?>;

        $scope.responsableselectionne;

        $scope.session = 0;
    
        $scope.masquerpresence = true;

        $scope.masquerpasse = true;

        $scope.masquergroupes = true;

        $scope.groupepromotion;

        $scope.codegroupe = -1;

        $scope.activiteselectionne = -1;

        $scope.afficheradmins = true;
        $scope.afficherresponsables = true;

        $scope.formatheure = function(heure){
            return heure.slice(0, -3);
        }

        $scope.setactselectionne = function(id){
            $scope.activiteselectionne = id;
        }

        $scope.setgroupe = function(id){
                $scope.codegroupe = id;            
        }

        $scope.show_params = function(activite) {
            $('#modal_mod_planif').modal('open');
            $('#id_act_plan').val(activite.id_activite_prevue);
            $('#mod_nom_act').val(activite.id_activite);
            $('#mod_nom_act').material_select();
            $('#mod_date_act').val(activite.date_activite);
            $('#mod_heure_deb').val(activite.heure_debut);
            $('#mod_participants_max').val(activite.participants_max);
            $('#mod_frais').val(activite.frais);
            $('#mod_endroit').val(activite.endroit);
            $('#mod_responsable').val(activite.responsable);
            $('#mod_responsable').material_select();
            $('.activer').addclass("active");
        }

        $('#select_session').on('change', function() {
            let x = $('#select_session').val();
            $('#select_session').val(x);
            $scope.session = x;
            $scope.$apply();
            $('#select_session').material_select();
        });




        $scope.pointsdebutforeleve = function(id) {

            let pts = 0;
            try {
                pts = $scope.points_debut.filter(function(el) {

                    return el.id_utilisateur == id;
                })[0].points_debut;

                if (pts > 5) {
                    return 5;
                }
            } catch (err) {}
            return parseint(pts);



        }

        $scope.penaliteforeleve = function(id){
            try{

            let pts = parseint($scope.penalites.filter(function(el) {
                    return el.id_utilisateur == id;
                })[0].penalite);
            return pts;
        }
            catch(err){
        
                return 0;
            }

        }


        $scope.pointsfinforeleve = function(id) {

            let pts = 0;
            try {
                pts = $scope.points_fin.filter(function(el) {

                    return el.id_utilisateur == id;
                })[0].points_fin;

                if (pts > 5) {
                    return 5;
                }
            } catch (err) {}
            return parseint(pts);
        }



        $scope.pointsbonusforeleve = function(id) {
            let pts_fin = 0;
            let pts_debut = 0;

            try {
                pts_fin = $scope.points_fin.filter(function(el) {
                    return el.id_utilisateur == id;
                })[0].points_fin;
            } catch (err) {}


            try {
                pts_debut = $scope.points_debut.filter(function(el) {

                    return el.id_utilisateur == id;
                })[0].points_debut;
            } catch (err) {}

            let pts_bonus = 0;

            if (pts_fin > 5) {
                pts_bonus += pts_fin - 5;
            }

            if (pts_debut > 5) {
                pts_bonus += pts_debut - 5;
            }

            if(pts_bonus > 5){
                return parseint(5);
            }else return parseint(pts_bonus);
        }

        $scope.pointsreguliersforeleve = function(id) {
            let pts_fin = 0;
            let pts_debut = 0;
            try {
                pts_fin = $scope.points_fin.filter(function(el) {
                    return el.id_utilisateur == id;
                })[0].points_fin;
            } catch (err) {}


            try {
                pts_debut = $scope.points_debut.filter(function(el) {

                    return el.id_utilisateur == id;
                })[0].points_debut;
            } catch (err) {}


            let pts_reg = 0;

            if (parseint(pts_debut) + parseint(pts_fin)  > 5) {
                pts_reg = 5;
            } else pts_reg = parseint(pts_debut) + parseint(pts_fin);
            
            return parseint(pts_reg);
        }




        $scope.pointsbonusensemble1foreleve = function(id) {

            let pts_fin = 0;
            let pts_debut = 0;
            try {
                pts_fin = $scope.points_fin.filter(function(el) {
                    return el.id_utilisateur == id;
                })[0].points_fin;
            } catch (err) {}


            try {
                pts_debut = $scope.points_debut.filter(function(el) {

                    return el.id_utilisateur == id;
                })[0].points_debut;
            } catch (err) {}


            let pts_reg = (parseint((parseint(pts_debut) + parseint(pts_fin))));

            console.log(pts_reg+" "+id)

            if (pts_reg > 5) {

                    return parseint(pts_reg - 5);
             
            }
            else return 0;



        }


        $scope.pointsensemble2 = function(id) {

            let pts_fin = 0;
            let pts_debut = 0;

            try {
                pts_fin = $scope.points_fin.filter(function(el) {
                    return el.id_utilisateur == id;
                })[0].points_fin;
                pts_fin = parseint(pts_fin);
            } catch (err) {}
            pts_fin = parseint(pts_fin);

            try {
                pts_debut = $scope.points_debut.filter(function(el) {
                    return el.id_utilisateur == id;
                })[0].points_debut;
                pts_debut = parseint(pts_debut);
            } catch (err) {}
            pts_debut= parseint(pts_debut);
            let pts_totaux = parseint(pts_fin) + parseint(pts_debut);

            if (pts_totaux > 5) {
                pts_totaux = 5;
            }


            return pts_totaux;


        }




        $scope.modifieractiviteprevue = function() {

            $.ajax({
                type: "post",
                url: "php_scripts/modifieractiviteprevue.php",
                data: {
                    'id_activite_prevue': $('#id_act_plan').val(),
                    'id_activite': $('#mod_nom_act').val(),
                    'date_act': $('#mod_date_act').val(),
                    'heure_act': $('#mod_heure_deb').val(),
                    'participants_max': $('#mod_participants_max').val(),
                    'frais': $('#mod_frais').val(),
                    'endroit': $('#mod_endroit').val(),
                    'responsable': $('#mod_responsable').val()
                }, //todo: change prof id
                success: function(data) {

                    alert(data);
                    if (data.trim() == "l'activité a été modifiée avec succès!") {
                        location.reload();
                    }



                },
                error: function(req) {
                    alert("erreur");
                }
            });


        }

        $scope.supprimeractivite = function(id) {



            if (confirm("vous êtes sur le point de supprimer cette activité, êtes vous sûr?") == true) {
                $.ajax({
                    type: "post",
                    url: "php_scripts/supprimeractivite.php",
                    data: {
                        'id_activite': id,
                    }, //todo: change prof id
                    success: function(data) {
                        location.reload();
                    },
                    error: function(req) {
                        alert("erreur");
                    }
                });


            }
        }

        $scope.modifieractivite = function(activite) {

            $('#id_mod_act').val(activite.id_activite);
            $('#nom_activite_mod').val(activite.nom_activite);
            $('#duree_mod').val(activite.duree);
            $('#point_mod').val(activite.ponderation);
            $('#description_mod').val(activite.commentaire);
            $('#modal_mod_new_activite').modal("open");
            $('#modal_mod_new_activite label').addclass("active");
        }

        $scope.modifiersession = function(session) {

            $('#id_session_mod').val(session.id_session);
            $('#nom_session_mod').val(session.nom_session);
            $('#deb_session_mod').val(session.debut_session);
            $('#mi_session_mod').val(session.mi_session);
            $('#fin_session_mod').val(session.fin_session);
            $('#modal_session_mod').modal('open');
            $('#modal_session_mod label').addclass("active");

        }

        $scope.niveauxadmin = ['administrateur', 'planificateur'];

        $scope.saveadmin = function() {
            $.ajax({
                type: "post",
                url: "php_scripts/updateadmin.php",
                data: {
                    'user': $('#utilisateurnivadmin').val(),
                    'admin': $('#niveauuser').val()
                }, //todo: change prof id
                success: function(data) {
                    location.reload();

                },
                error: function(req) {
                    alert("erreur");
                }
            });

        }

        $scope.now = new date();

        $scope.todate = function(datemod) {
            return new date(datemod);
        }

        $scope.scopeprint = function(val) {
        }


        $scope.activitefromid = function(id) {

            let act = $scope.activites.filter(function(ac) {
                return ac.id_activite == id;
            })[0];

            return act;

        }



        $scope.groupefromid = function(id) {

            let gr = $scope.groupes.filter(function(gr) {
                return gr.id_groupe == id;
            })[0];

            return gr;

        }


        $scope.adminlevelfromid = function(admin) {

            let adminlevel;

            switch (admin) {
                case '2':
                    adminlevel = 'administrateur';
                    break;
                case '1':
                    adminlevel = 'responsable';
                    break;
                default:
                    adminlevel = 'utilisateur régulier';

            }
            return adminlevel;

        }


        $scope.elevesdansgroupe = function(groupe) {
            return $scope.eleves.filter(function(el) {

                return el.id_groupe == groupe && el.code_acces == "";
            });
        }

        $scope.elevefromid = function(id) {
            return $scope.eleves.filter(function(el) {

                return el.id_utilisateur == id;
            })[0];
        }




        $scope.getelevesforactiviteprevue = function(activite) {

            let liste_el_ac = ($scope.eleves_activites.filter(function(ac) {
                return ac.id_activite_prevue == activite;
            }));


            var listeid = liste_el_ac.map(function(a) {
                return a.id_utilisateur;
            });

            let arr = [];

            for (var i = 0; i < listeid.length; i++) {
                arr.push($scope.elevefromid(listeid[i]));
            }
            return arr;

        }

        $scope.getpresenceforeleve = function(activite_prevue, eleve) {
            try {
                let present = ($scope.eleves_activites.filter(function(ac) {
                    return ac.id_activite_prevue == activite_prevue && ac.id_utilisateur == eleve;
                }))[0].present;

                if (present == 1) {
                    return true;
                } else return false;
            } catch (err) {
                return false
            }


        }

        $scope.annuleractivite = function(activite) {

            if (confirm("vous êtes sur le point de supprimer cette activité, êtes vous sûr?") == true) {
                $.ajax({
                    type: "post",
                    url: "php_scripts/annuleractivite.php",
                    data: {
                        'id_activite': activite,
                    }, //todo: change prof id
                    success: function(data) {
                        location.reload();

                    },
                    error: function(req) {
                        alert("erreur");
                    }
                });

            }




        }



        $scope.elevefromid = function(id) {


            let elev = $scope.eleves.filter(function(el) {
                return el.id_utilisateur == id;
            })[0];

            return elev;

        }


        $scope.enregistrerpresence = function(activite_prevue) {
            var values = new array();
            $.each($("input[name='presenceactivite']:checked"), function() {
                values.push($(this).val());
            });

            alert(values);  

            $.ajax({
                type: "post",
                url: "php_scripts/prendrepresence.php",
                data: {
                    'presents': values,
                    'activite': $scope.activiteselectionne
                }, //todo: change prof id
                success: function(data) {
                    location.reload();
                },
                error: function(req) {
                    alert("erreur");
                }
            });



        }


        $scope.comptesaveccodedansgroupe = function(groupe) {
            return $scope.eleves.filter(function(el) {
                return el.id_groupe == groupe && el.code_acces != "";
            });
        }



        $scope.comptesadmin = function(groupe) {
            return $scope.eleves.filter(function(el) {
                return el.administrateur >= 1 && el.code_acces == "";
            });
        }



        $scope.generercodepourgroupe = function(groupe, nb_codes) {

            $.ajax({
                type: "post",
                url: "php_scripts/generercode.php",
                data: {
                    'admin': 0,
                    'id_groupe': $scope.codegroupe,
                    'nb_codes': $("#codegroupe").val()
                },
                success: function(data) {

                    location.reload();

                },
                error: function(req) {
                    alert("erreur");
                }
            });

        }


        $scope.generercodepourgroupe0 = function(){

             $.ajax({
                type: "post",
                url: "php_scripts/generercode.php",
                data: {
                    'admin': 0,
                    'id_groupe': $scope.codegroupe,
                    'nb_codes': $("#codegroupe0").val()
                },
                success: function(data) {
                    alert(data);
                    location.reload();

                },
                error: function(req) {
                    alert("erreur");
                }
            });

        }

        $scope.generercodeadmin = function(nb_codes) {

            $.ajax({
                type: "post",
                url: "php_scripts/generercode.php",
                data: {
                    'admin': $('input[name=niveauadmin]:checked').val(),
                    'id_groupe': 'null',
                    'nb_codes': $("#codeadmin").val()
                },
                success: function(data) {

                    location.reload();
                },
                error: function(req) {
                    alert("erreur");
                }
            });

        }

        $scope.setpromotionid = function(groupe) {

            $scope.groupepromotion = $scope.elevesdansgroupe(groupe);



        }


        $scope.promoteuser = function(id_user) {

            if (confirm("êtes-vous sûr de vouloir promouvoir cet utilisateur?"))
                $.ajax({
                    type: "post",
                    url: "php_scripts/updateadmin.php",
                    data: {

                        'user': id_user,
                        'admin': 1


                    }, //todo: change prof id
                    success: function(data) {

                        location.reload();

                    },
                    error: function(req) {
                        alert("erreur");
                    }
                });

        }

        $scope.demoteuser = function(id_user){
                        if (confirm("êtes-vous sûr de vouloir rétrograder cet utilisateur?"))
                $.ajax({
                    type: "post",
                    url: "php_scripts/updateadmin.php",
                    data: {

                        'user': id_user,
                        'admin': 0


                    }, //todo: change prof id
                    success: function(data) {

                        location.reload();

                    },
                    error: function(req) {
                        alert("erreur");
                    }
                });

        }



        $scope.creergroupe = function() {

            $.ajax({
                type: "post",
                url: "php_scripts/creergroupe.php",
                data: {
                    'nomgroupe': $("#nomgroupe").val(),
                    'id_prof': 0,
                    'nb_codes': $("#rangeeleves").val(),
                    'ensemble': $("#ensemble").val(),
                    'session': $("#session").val()

                }, //todo: change prof id
                success: function(data) {
                    location.reload();

                },
                error: function(req) {
                    alert("erreur");
                }
            });
        }




        $scope.ouvrirmodalmodifierpermission = function(id_admin, niveau) {

            $("#utilisateurnivadmin").val(id_admin);
            $('#modal_niveauadmin').modal('open');
            $('#niveauuser').val(niveau).change();
            $('#niveauuser').material_select();

        }

        $scope.print = function(groupe) {
            var prtcontent = document.getelementbyid('codesgroupe' + groupe);
            var winprint = window.open('', '', 'left=0,top=0,width=1920,height=2000,toolbar=0,scrollbars=0,status=0');
            winprint.document.write("liste des codes d'accès <br>" + prtcontent.innerhtml);
            winprint.document.close();
            winprint.focus();
            winprint.print();
            winprint.close();
        }

        $scope.supprimergroupe = function(groupe, nomgroupe) {

            var nom_groupe = prompt("pour confirmer la suppression, veuillez entrer le nom du groupe", "");

            if (nom_groupe == nomgroupe) {
                $.ajax({
                    type: "post",
                    url: "php_scripts/supprimergroupe.php",
                    data: {
                        'id_groupe': groupe,
                    }, //todo: change prof id
                    success: function(data) {

                        location.reload();
                    },
                    error: function(req) {
                        alert("erreur");
                    }
                });
            }else alert("le groupe saisi ne correspond pas au groupe que vous souhaitez supprimer. la suppression est annulée")
        }

    });

    $("#selectresponsable").val($("#selectresponsable option:first").val());
    $('#selectresponsable').material_select();

</script>