<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MinculturaUser;
use App\Models\Audience;
use App\Models\User;
use App\Models\Agendas;
use App\Models\Fair;
use App\Models\RoleUserFair;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Notification;
use App\Notifications\DynamicNotification;
use App\Notifications\SuccessAgendaRegistration;
use Illuminate\Support\Facades\Validator;

class MinculturaUserController extends Controller
{
    public function index(Request $request){

        $user = auth()->guard('api')->user();

        //query mincultura user data
        $mincultura = MinculturaUser::where('user_id',$user->id)->first();
        
        $audience_user = Audience::with('agenda.category')
        ->with('user.user_roles_fair')
        ->where('user_id',$user->id)->get();
        
        //query available list 
        $queryMeeting = Agendas::select('id','title','description', 'description_large','duration_time','start_at','timezone','audience_config','resources','category_id','price');
        $queryMeeting = $queryMeeting->with('audience.user.user_roles_fair', 'invited_speakers.speaker.user', 'category' );
        $queryMeeting = $queryMeeting->where('fair_id',$request['fair_id'])->orderBy('start_at')->get();

        $meetings = [];

        forEach($queryMeeting as $agenda) {
            if($agenda->category->name == 'Taller' || $agenda->category->name == 'Taller_M'||$agenda->category->name == 'Taller_T') {
                $count = 0;
                $guest = 25;
                forEach($agenda->audience as $audience) {
                    if($audience->user->user_roles_fair) {
                        forEach($audience->user->user_roles_fair as $rol) {
                            if($rol->pivot->role_id == 4) {
                                $count++;
                            }
                        }   
                    }
                }
                unset($agenda->audience);
                $agenda->full = $count >= $guest ? '1': '0';
                array_push($meetings,$agenda);
            }

            
        }

        //return data with state available meetings
        return [
            'success' => 201,
            'data' => $mincultura,
            'audience' => $audience_user,
            'meetings' => $meetings
        ];

    }

    public function register(Request $request, $fair_id){

        $user = auth()->guard('api')->user();
        $sendMail = false;
        $fair = Fair::find($fair_id);

        //query mincultura user data
        $minculturaUpdate = false;
        $mincultura = MinculturaUser::where('user_id',$user->id)->first();
        if($mincultura) {
            $mincultura->documento_tipo = $request['docType'];
            $mincultura->documento_numero = $request['docNumber'];
            if($request['emailAdditional'] != null )
            {
                $mincultura->correo_electronico_adicional = $request['emailAdditional'];
            }  
            else{
                $mincultura->correo_electronico_adicional = '';
            }

            $mincultura->save();
            $minculturaUpdate = true;
        }

        //query register in agenda
        $audience_user = null;
        $meetings = [];
        $newAudience = false;
        $audience = null;
        $agenda_id = $request['agendaId'];        
        if($agenda_id) {
            $audience_user = Audience::where('user_id',$user->id)->get();
            if(!$audience_user) {
                $newAudience = true;
            }
            else {
                $newAudience = true;
                foreach ($audience_user as $aud) {
                    if($aud->agenda_id == $agenda_id) {
                        $audience = $aud;
                        $newAudience = false;
                    }
                }
            }

            if($newAudience) {
                //Audience::where('user_id',$user->id)->delete();
                $audience = new Audience();
                $audience->agenda_id = $agenda_id;
                $audience->email = $user->email;
                $audience->user_id = $user->id;
                $audience->check = 1;
                $newAudience = true;
                $audience->save();

                try{
                    $agenda = Agendas::find($agenda_id);

                    $date = date("d/m/Y", $agenda->start_at);
                    $dateHour = date("H:i", $agenda->start_at);
                    $day = array("Domingo","Lunes","Martes","Miércoles","Jueves","Viernes","Sábado");
                    $dayFormat = $day[date("w",$agenda->start_at)]. ', '. $date;

                    $duration = ['15'=>'15 min','30'=>'30 min','45'=>'45 min','60'=>'1 hora','90'=>'1 hora y 30 min','120'=>'2 horas','150'=>'2 horas y 30 min','180'=>'3 horas','210'=>'3 horas y 30 min','240'=>'4 horas', '270'=>'4 horas y 30 min','300'=>'5 horas','330'=>'5 horas y 30 min','360'=>'6 horas','390'=>'6 horas y 30 min'];
                    $durationStr = $duration[$agenda->duration_time]; 

                    if (App::environment('production') || App::environment('sendEmail') ) {
                      Notification::route('mail', $user->email)
                        ->notify(new SuccessAgendaRegistration($fair, $user->email, $agenda, $dayFormat, $durationStr, $dateHour));
                      $sendMail = true;
                    }

                    return [
                        'success' => 201,
                        'minculturaUpdate' => $minculturaUpdate,
                        'audience' => $audience,
                        'newAudience' => $newAudience,
                        'sendMail' => $sendMail,
                        'dayFormat' => $dayFormat,
                        'durationStr' => $durationStr
                    ];
        
                }catch (\Exception $e){
                    return response()->json(['message' => 'Error enviando el correo electrónico .'.' '.$e], 403);
                }
            }            
        }
        
        //return data with state available meetings
        return [
            'success' => 201,
            'minculturaUpdate' => $minculturaUpdate,
            'audience' => $audience,
            'newAudience' => $newAudience,
            'sendMail' => $sendMail
        ];
    }

    public function agendaAvailability(Request $request){

        $agenda_id = $request['agenda_id'];
        
        $agenda = Agendas::select('id','title','description', 'description_large','duration_time','start_at','timezone','audience_config','resources','category_id','price');
        $agenda = $agenda->with('audience.user.user_roles_fair', 'invited_speakers.speaker.user', 'category' );
        $agenda = $agenda->where([['fair_id',$fair_id],['agenda_id',$agenda_id]])->first();

        if($agenda->audience_config == "5"||$agenda->category->name == 'Taller') {
            $count = 0;
            $guest = 25;
            forEach($agenda->audience as $audience) {
                if($audience->user->user_roles_fair) {
                    forEach($audience->user->user_roles_fair as $rol) {
                        if($rol->pivot->role_id == 4) {
                            $count++;
                        }
                    }
                }
            }
            
            return $count >= $guest;
        }
        
        return true;
    } 

    public function showRegister(Request $request){

        $fair_id = $request['fair_id'];
        if($fair_id==9999) {
            
            /*$count = Audience::truncate();
            
            return [
                'success' => 201,
                'message'=>'toda la audiencia borrada'
            ];*/
        }

        if($fair_id==1) {

            $users = User::with('audience.agenda.category','mincultura','roles_fair')->get();
            return [
                'success' => 201,
                'arrayUser' => $users                
            ];
        }
        if($fair_id==2) {

            $mincultura = MinculturaUser::get();
            $arrayUserMin = [];
            forEach($mincultura as $min) {
                
                $user = User::find($min->user_id);
                if(!$user) {
                    array_push($arrayUserMin, $min);
                }
                
            }   
            
            $roles = RoleUserFair::get();
            $arrayUserRol = [];
            forEach($roles as $rol) {
                
                $user = User::find($min->user_id);
                if(!$user) {
                    array_push($arrayUserRol, $rol);
                }
                
            }   
            
            return [
                'success' => 201,
                'arrayUserMin' => $arrayUserMin,
                '$arrayUserRol'=> $arrayUserRol                
            ];
        }

        else if($fair_id==3) {
            $mail = "andrescarvajal354@gmail.com";
            $user = User::where('email',$mail)->first();
            $user2 = User::where('email','davithc01@gmail.com')->first();
            $user3 = User::where('email','cuentapruebasali2@gmail.com')->first();
            $user3->password = $user->password;
            $user3->save();
            $user->password = $user2->password;
            $user->save();

            return [
                'success' => 201,
                'mail' => $mail,
                'user' => $user
            ];

        }
        else if($fair_id==4) {
            $mail = "andrescarvajal354@gmail.com";
            $user = User::where('email',$mail)->first();
            $user2 = User::where('email','davithc01@gmail.com')->first();
            $user3 = User::where('email','cuentapruebasali2@gmail.com')->first();
            $user->password = $user3->password;
            $user->save();
            return [
                'success' => 201,
                'mail' => $mail,
                'user' => $user
            ];
        }
        else if($fair_id==555) {
            $email = 'sanp7276@gmail.com';
            $user = User::where('email', $email)->first();
            $audience = new Audience();
            $audience->agenda_id = 2;
            //Conferencia inaugural "Lengua oral y experiencia humana: perspectivas para la vida individual y social"
            $audience->email = $user->email;
            $audience->user_id = $user->id;
            $audience->check = 1;
            $audience->attendance = 4;
            $created_at = Audience::where('agenda_id',$audience->agenda_id)->first();
            $audience->created_at = $created_at->created_at;
            $audience->save();
    
            $audience = new Audience();
            $audience->agenda_id = 5;
            //Panel "Prácticas de la oralidad en Colombia: diversidad lingüística y cultural"
            $audience->email = $user->email;
            $audience->user_id = $user->id;
            $audience->check = 1;
            $audience->attendance = 65;
            $created_at = Audience::where('agenda_id',$audience->agenda_id)->first();
            $audience->created_at = $created_at->created_at;
            $audience->save();
    
            $audience = new Audience();
            $audience->agenda_id = 3;
            //Conferencia "La oralidad en las políticas públicas de lectura, escritura, oralidad y bibliotecas: el caso de Colombia"
            $audience->email = $user->email;
            $audience->user_id = $user->id;
            $audience->check = 1;
            $audience->attendance = 0;
            $created_at = Audience::where('agenda_id',$audience->agenda_id)->first();
            $audience->created_at = $created_at->created_at;
            $audience->save();
    
            $audience = new Audience();
            $audience->agenda_id = 14;
            //Panel "Creación de contenidos sonoros para la educación y la cultura"
            $audience->email = $user->email;
            $audience->user_id = $user->id;
            $audience->check = 1;
            $audience->attendance = 0;
            $created_at = Audience::where('agenda_id',$audience->agenda_id)->first();
            $audience->created_at = $created_at->created_at;
            $audience->save();
    
        }
        return true;
    } 

    public function notify(Request $request){

        /*$validator = Validator::make($request->all(), [
            'fair_id'=>'required',
            'role_id'=>'required',
            'title'=>'required',
            'subject'=>'required'
        ]);

        if ($validator->fails()) {
            return [
                'success' => false,
                'data' => $validator->errors(),
            ];
        }

        $data = $validator->validated();

        $fair_id = $data['fair_id'];
        $fair = Fair::find($fair_id);
        $role_id = $data['role_id'];
        $title = $data['title'];
        $subject = $data['subject'];*/

        $fair_id = "1";
        $fair = Fair::find($fair_id);
        $role_id = "4";
        $title = 'Encuesta de percepción del VIII Congreso Nacional de Bibliotecas Públicas "Oralidades y culturas orales en las bibliotecas del sur"';
        $subject = "VIII Congreso Nacional de Bibliotecas Públicas";

        /*$users = User::with('user_roles_fair')->whereHas('user_roles_fair',function($query)use($role_id){
			$query->where('role_id',$role_id);
		})->get();*/

        $users = User::get();
        

        $emails = ["bibliotecaelpedregal@gmail.com","biblioteca@lebrija-santander.gov.co","afrejad@gmail.com","adrianabernardahj@gmail.com","abetancur77@gmail.com","adridi23@yahoo.es","agrisales48@hotmail.com","amartinezr@mincultura.gov.co","patycultural2030@gmail.com","bibliotecamunicipal@oiba-santander.gov.co","agustindecolombia@gmail.com","biblioteca@tangua-narino.gov.co","carotrujillod@gmail.com","bibliotecapublica@mallama-narino.gov.co","bigalan91@hotmail.com","bibliotecaitinerantebuenavista@gmail.com","aldorom1076@gmail.com","alegimos1930@gmail.com","alejaprado@gmail.com","alejaquinterocardona@gmail.com","bibliomirafloresb@gmail.com","aeparada@fibertel.com.ar","promotorhparra@gmail.com","alejo.latp@gmail.com","alcg862@gmail.com","alexkapot3@gmail.com","alfflo@bibliovalle.gov.co","biblioteca@sancarlosdeguaroa-meta.gov.co","ars3622@gmail.com","almadea8@gmail.com","amandag.30@hotmail.com","intersticioalalba@gmail.com","amparo.davila@gmail.com","ap2020@gmail.com","ani.parramr@gmail.com","biblioteca.gameza01@gmail.com","andene62@hotmail.com","anajose.ps@gmail.com","3solesluna@gmail.com","crisyana1997@gmail.com","gutierreztatisanalorena@gmail.com","amajaloa@gmail.com","anaosorioolaya@gmail.com","anamoren1985@gmail.com","anamilena0383@gmail.com","anamiruge@gmail.com","carolinadelatorre2009@hotmail.com","bibliotecapublicaviracacha@gmail.com","andreatamayogonzalez@gmail.com","andrescabrerahiguita@gmail.com","andrescarvajal354@gmail.com","andresvalencia8905@gmail.com","andresfelipesanabriamolina@gmail.com","cgandres31@gmail.com","mateo_santero@hotmail.com","biblioteca@jerico-boyaca.gov.co","angela.garcia@biblored.gov.co","biblioteca.nuevocolonboyaca@gmail.com","urreaangela@gmail.com","yumico0007@gmail.com","angelica880719@gmail.com","sayago.17@gmail.com","anggievictoria@outlook.es","angiecardozo0608@gmail.com","angievalery0329@gmail.com","angieppr15@gmail.com","valenciagonzalez@gmail.com","anyi.roa9225@unaula.edu.co","bibliotecamunicipal.suaza@gmail.com","anyisorey@gmail.com","bibliotecamunicipaldeachi@gmail.com","ladiosa_2968@hotmail.com","armandofer2013@gmail.com","arnoldohoyosn69@gmail.com","arthurfajaron@gmail.com","atsualca123@gmail.com","bibliotecamongualuis@gmail.com","bibliotecapublicapanqueba@gmail.com","joana.araque@hotmail.com","baguilar@atlantico.gov.co","berthagomez8@outlook.com","frbertha@hotmail.com","bcardenas@bibliotecanacional.gov.co","bibidelbianco@hotmail.com","bpgirardota@gmail.com","cea.bzuniga@gmail.com","edibar53@hotmail.com","biblioteca@turmeque-boyaca.gov.co","cultura@elaguila-valle.gov.co","borixtejada@gmail.com","brayanaguillon4@gmail.com","albertoc8@hotmail.com","vagamundo44@gmail.com","subdir.contenidos@bpp.gov.co","castellanoscf95@gmail.com","cargasa9@gmail.com","cardelacruz_2007@hotmail.com","karloz.lozano@gmail.com","cavisabel@gmail.com","digitalizacion@itsa.edu.co","carmediospino1959@hotmail.com","carmenyolanda8@yahoo.com","menchyta.alvarado@gmail.com","carmher1264@gmail.com","carol888motta@gmail.com","karolinne920926@gmail.com","lancheros.carolina@gmail.com","clema@bibliotecanacional.gov.co","pimienta94@gmail.com","casildamartinez1@hotmail.com","promotorcplata@gmail.com","catalinaavilareyes@gmail.com","cathezar15@gmail.com","cefemezar@gmail.com","cesaraugustomurcia@gmail.com","cesarjtalledo@gmail.com","crism082090@gmail.com","claramerchan@yahoo.com","claramayarojas@gmail.com","claudet50@hotmail.com","claudia.ceballosruiz.25@gmail.com","cmchavesc@unal.edu.co","cgiraldo122a@gmail.com","patylav2006@gmail.com","lunafestinio@hotmail.com","c_rodas@yahoo.com","tutorcardila@bibliotecanacional.gov.co","aguafuertess@gmail.com","cristosruiza@misena.edu.co","dailis.dcb@gmail.com","biblioteca@valledesanjuan-tolima.gov.co","dalysmariaospinocuesta@gmail.com","damarisortega1958@hotmail.com","bicibiblioteca@gmail.com","bibliotecapublicadagua@gmail.com","daniel-vergara31@hotmail.com","dafrodrigueza@correo.udistrital.edu.co","danielamurillo0918@gmail.com","daniele.achilles@unirio.br","danthoryofficial@gmail.com","dariosenen@yahoo.es","biblioteca.municipal@jamundi.gov.co","darwin.oliveros@gmail.com","bibliotecacaldasboyaca@gmail.com","deicyzayas87@gmail.com","killkatinauku@gmail.com","deisson@misena.edu.co","deypepa@yahoo.es","delishernandezhernandez@gmail.com","biblioteca@garagoa-boyaca.gov.co","johanhdez05@gmail.com","bisdeytal96@gmail.com","dianacarocarranza@hotmail.com","dolordebariga@gmail.com","dianac.velozab@uqvirtual.edu.co","dianaestrada387@gmail.com","dianital_mar@hotmail.com","dianamarcelaastudilloquinayas@gmail.com","dianamarcelagor@gmail.com","dmguzmanpoveda@gmail.com","angel5230@hotmail.com","dina4693@hotmail.com","dianarios26@gmail.com","rossy2882@yahoo.es","lic.dianetpf@hotmail.com","dbecerga@banrep.gov.co","bibliotecatiberiovanegaspinzon@gmail.com","bernalsanchezd@yahoo.com.co","dali.carmina@gmail.com","dora.alzate@udea.edu.co","dorasora64@gmail.com","dorae28@hotmail.com","bibliotecacorrales18@gmail.com","dorislg3@yahoo.es","sirleyortega@gmail.com","dulcineabenito@gmail.com","dunizambrano05@gmail.com","andreschaverra@gmail.com","edgarvillalba1962@hotmail.com","joseedgardo068@hotmail.com","edilma.soler@gmail.com","katerine0142010@hotmail.com","edithdulcey@gmail.com","edivaram@gmail.com","biblio.elpenon@gmail.com","elnenemu04@gmail.com","edwing.arciniegas@hotmail.com","efragestorcultural@yahoo.es","eglismarianobadillo@gmail.com","egnamf1976@hotmail.com","montanaedna03@gmail.com","gestorbetulia@gmail.com","bibliotecamunicipal@lacruz-narino.gov.co","eliana88enriquez@gmail.com","bibliotecacontratacion@gmail.com","biblioteca@sativanorte-boyaca.gov.co","elizabethjm1964@hotmail.com","eliza.4315@hotmail.com","evargasramirez@yahoo.es","bibliotkcumaribo@gmail.com","biblioteca@saneduardo-boyaca.gov.co","elsa.arcosl13@gmail.com","bibliotecasanestanislao@hotmail.es","e-cabarcas@hotmail.com","erikaospinaro@gmail.com","parramoreno29@hotmail.com","erikaroldan5880@gmail.com","erika_paipa_ramirez@hotmail.com","erikapaola1497@gmail.com","erikaurbanon@hotmail.com","bibliotecapublicalandazuri@gmail.com","estebancastanedainfo@gmail.com","estiben.mosquera@est.iudigital.edu.co","eusebiodiazm@hotmail.com","fabio.gomez@utp.edu.co","facundoleonelmercadante@gmail.com","feliperolz851@gmail.com","fesarji75@hotmail.com","fernando.mora.a@gmail.com","florangelariverat@gmail.com","andreap12003@gmail.com","frans.acevedo@imct.gov.co","gabritique@gmail.com","gabi061589@gmail.com","geraldinelpr28@gmail.com","gersaenz@hotmail.com","bibliotecapublicasucre@gmail.com","guio.0209@hotmail.com","glamagoz@gmail.com","gledysromerortega@hotmail.com","gloriamoralesval@gmail.com","biblioteca@montenegro-quindio.gov.co","gloriamorales1384@gmail.com","glosoreca@gmail.com","greisrios@hotmail.com","carpiochamarraguillermo@gmail.com","ggalindocastillo@gmail.com","sethhector@gmail.com","bibliotecamunicipal@laceja-antioquia.gov.co","helenap23@hotmail.com","haosorior@unal.edu.co","hugonevardo@hotmail.com","geovanyarroyo6@gmail.com","dilbarosa@yahoo.es","ildebur@hotmail.com","casadelacultura@almeida-boyaca.gov.co","irismendoza25d@gmail.com","isabelvidal69@hotmail.com","bibliotecabeteitiva21@gmail.com","ivonne.rodriguez@chia.gov.co","jaime.bornacelly@udea.edu.co","mejairo6@gmail.com","ygalvis08@gmail.com","magysg463@gmail.com","janavast@correo.udistrital.edu.co","yballesteros160@gmail.com","jcbarberan@uce.edu.ec","lopezjean30@gmail.com","jahrapha@outlook.com","osorio.jeffer@gmail.com","lozanojeimmy28@gmail.com","jeimychavezw@gmail.com","jeison.saenz@outlook.com","bec.arama@hotmail.com","jenny_8246@hotmail.com","jbernal@bibliotecanacional.gov.co","biblioteca@caracoli-antioquia.gov.co","biblioteca@pinchote-santander.gov.co","jessika.cano@udea.edu.co","cormiti8@gmail.com","biblioteca_alban@comfacauca.com","benkojohira@gmail.com","jhonypaz1218@gmail.com","joan.belalcazar1@gmail.com","jorozco@bibliotecanacional.gov.co","johanalbertoe72@gmail.com","johanbedc@gmail.com","jmila@ccb.edu.co","juanaescobarsalazar@hotmail.es","biblioteca@tota-boyaca.gov.co","johalobolobo@gmail.com","cuaspudj80@hotmail.com","fredygranados2@gmail.com","jjcasasmonti@gmail.com","johnatan2501@gmail.com","biblioteca@chiquiza-boyaca.gov.co","fotoleoocamonte@gmail.com","jorgeguezjayac@gmail.com","jtoledo@eb.com","alexanderrincon924@gmail.com","bibliotecajesuszaratemoreno@gmail.com","joseperez8607@gmail.com","josefsimancas@hotmail.com","hommelire@gmail.com","joselmb1@hotmail.com","jlrr_pp@hotmail.com","jlvargasl@ut.edu.co","mauricioquiceno.cine@gmail.com","jomimague1988@hotmail.com","auxiliar@cerrodesanantonio-magdalena.gov.co","jovannicantero@hotmail.com","naoxmusic@gmail.com","tabimba1010@gmail.com","jctobonc@academia.usbbog.edu.co","juan.lopera9@udea.edu.co","juanfego8723@gmail.com","juanfe981122@gmail.com","juan.preciado@barbosa.gov.co","juanguillermomesa21@gmail.com","bibliotecamunicipal@elcarmendeviboral-antioquia.gov.co","jnsebr@gmail.com","jvicentinos@hotmail.com","juanitajaraba123@gmail.com","jjipe0917@gmail.com","juanacarolinariverasilva@gmail.com","alzatemarinjuanita26@gmail.com","juanita.insuasty@gmail.com","judymonrreal57@gmail.com","judithmoreperdo@hotmail.com","bibliotecapublicasanmateo15673@gmail.com","culturayturismo@zapatoca-santander.gov.co","julianpereza@gmail.com","jhernandezs@bibliotecanacional.gov.co","juliethnavia27@gmail.com","j.ully1506@hotmail.com","karelymejia75@gmail.com","kp-ramos@javeriana.edu.co","romeokarol2020@gmail.com","katiard1971@gmail.com","katirisdelcarmenpadilla@gmail.com","keygonzalezc0757@gmail.com","kellyfermir@gmail.com","cantillomunoz1@gmail.com","kellyscaballerov@gmail.com","kevinmamian.2001@gmail.com","keylamaria_14@hotmail.com","krystelleon@gmail.com","ladymarcelagallo@gmail.com","lasirpaes2@gmail.com","laudisleneis@hotmail.com","lauditvco@gmail.com","lmrojas5@misena.edu.co","catalina1809@gmail.com","lauracorrea9211@hotmail.com","bibliotecaanitamartinez@gmail.com","laurajulianamantilla@gmail.com","laura.velasquez3@udea.edu.co","laurasalazarnieva@gmail.com","barreraleonlauriss@gmail.com","serlaus1505@gmail.com","ljcastano@sgc.gov.co","marianestrada189@gmail.com","lpaolasoler@gmail.com","leysanchez2015@gmail.com","biblioteca.jabm.hatosantander@gmail.com","biblioteca@filandia-quindio.gov.co","leidysorozcocueto@hotmail.com","read.yoss1502@gmail.com","lesazan16@gmail.com","lcuaces@unal.edu.co","lida059@gmail.com","lidagonzalez0208@gmail.com","libibliotenza@gmail.com","bibliotecacervantespacora@gmail.com","biblioteca@guateque-boyaca.gov.co","ligiscaliz@yahoo.es","bipuviross@hotmail.com","lilianaramirez-09@hotmail.com","biblioteca@vetas-santander.gov.co","aliliqortiz@gmail.com","lilitapasco@hotmail.com","linacarolina2303@gmail.com","limajo1711@gmail.com","limarceospi@gmail.com","linamarqq@hotmail.com","linandina87@gmail.com","linagomezb@hotmail.com","naharay15@gmail.com","liyudiaz4@gmail.com","bibliotecas@culturameta.gov.co","loreinespatricia@gmail.com","lorenacarreon@ymail.com","locepe@hotmail.com","lyongtorres20@gmail.com","lorenavalencia2626@gmail.com","lucamairiarte@hotmail.com","luceny2078@gmail.com","olgaluciacalderon@gmail.com","luisoyola01@gmail.com","ticosanchezarias@yahoo.es","biblioteca@tibasosa-boyaca.gov.co","bibliotecatorovalle@gmail.com","luisfernandome942@gmail.com","luisguzman196321@hotmail.com","luis.ignacioc58@gmail.com","jair.html@hotmail.com","olivavillar92@gmail.com","lavos.vegambre@gmail.com","luisacerquera18@gmail.com","luisaleon31@hotmail.com","lucescastillo18@gmail.com","luzap06@hotmail.es","angegaba03@gmail.com","luzalopezg@gmail.com","dannyvelasquez166@gmail.com","luzdary.jim1977@gmail.com","biblioteca@sansebastiandemariquita-tolima.gov.co","luzstellapm24@hotmail.com","cardeluz1974@hotmail.com","ingridcifucas@gmail.com","biblioteca@gamarra-cesar.gov.co","marina7461@hotmail.com","biblioteca@lavictoria-boyaca.gov.co","luz.galvis@correounivalle.edu.co","bedoyabedoyaluzmirella@gmail.com","fluzrene@gmail.com","palmarbiblioteca@gmail.com","luza.martinez@gmail.com","lydapatriciaespana@gmail.com","maicolfajardo2020@gmail.com","mallerly2010@gmail.com","mahf0312@hotmail.com","biblioteca.siachoque@gmail.com","sharalilith@gmail.com","marcela152010@hotmail.com","marcelarey100@hotmail.com","amarcela99@gmail.com","iex.montano.rivera@gmail.com","mrcastromarcoa@gmail.com","mingi03kok@gmail.com","margarita.estrada@chia.gov.co","margaritavillada80@gmail.com","ospinam05@gmail.com","margabo55@hotmail.com","biliotecagabrielgarciamarquezb@gmail.com","biblioteca.ictsangil@gmail.com","aleja2330@gmail.com","malepec@gmail.com","malejavarmo@gmail.com","mrincon@bibliotecanacional.gov.co","aliciacabrejoparra@gmail.com","mahc7028@gmail.com","lika.kiho@gmail.com","bibliotecapublica@sanjuanito-meta.gov.co","mariaastrid2017@gmail.com","brisantacruzdelbayano@gmail.com","luisaf46@hotmail.com","mariacampo1@yahoo.com","casadelacultura@sanmarcos-sucre.gov.co","mariacristinae461@gmail.com","mriosl@comfamiliar.edu.co","bibliotecapublicadecuitiva@gmail.com","mariaelvirapardovargas@gmail.com","sanbenitobibliotecamunicipal@gmail.com","nandaramirez2288@hotmail.com","congresonalrnbp@bibliotecanacional.gov.co","rojascuervom@gmail.com","lecturaviva@gmail.com","maadrgreveron@gmail.com","pilarsanchezbautista@hotmail.com","mcardonar@bibliotecanacional.gov.co","marmartinez110@gmail.com","marilucita131060@gmail.com","bibliotecapublica@jenesano-boyaca.gov.co","marcerhenals@gmail.com","marianebura@gmail.com","norabuendia22@hotmail.com","bibliolecturayop@hotmail.es","mapyvasquezgallego@gmail.com","lamaye@saberpopular.org","bpmnunchia@gmail.com","leerlibera2013@gmail.com","teresitaposteridad@gmail.com","trinizapata@yahoo.com","mmunozl@ut.edu.co","hojarascaensepia@gmail.com","marianagomezgutierrez97@gmail.com","bibliotecatibana804@gmail.com","biblioteca.algeciras@gmail.com","marcoro19@hotmail.es","majobra73@hotmail.com","bibliojosebolivartoro@hotmail.com","alexcg223@gmail.com","MAFELO2017@GMAIL.COM","biblioteca@ortega-tolima.gov.co","marlen.lopera@udea.edu.co","marlyarrias18@gmail.com","ebanno2007@yahoo.es","lejodani1970@gmail.com","myudym@gmail.com","marthaaliciam5@gmail.com","marthacaraballoc97@gmail.com","tutormrestrepo@bibliotecanacional.gov.co","teachermarttin19@hotmail.com","elmarto1964@hotmail.com","panchitamesu@gmail.com","ascenet04@gmail.com","maryzuro22@gmail.com","mayavees2630@gmail.com","mayramamc68@hotmail.com","mayriscq87@hotmail.com","melyramirez06@gmail.com","yinet_21@hotmail.com","bibliotecapublicaj.a.rcharta@gmail.com","fzmilena@gmail.com","milear13@hotmail.com","manejodocumental@bibloamigos.org","alexalop68@hotmail.com","yolimagr@yahoo.es","missellrangel@gmail.com","biblioteca@sanmartin-meta.gov.co","mercadonoblemoises@gmail.com","alexhamuyuy99@gmail.com","monicalinares1999@gmail.com","vivasalbanmonicalucia@gmail.com","monica.m.martinez@correounivalle.edu.co","lazaroz71@gmail.com","mokisscape@gmail.com","mypimo65@gmail.com","patolina2806@hotmail.com","biblioteca@comfamiliarnarino.com","tutorpacificocastaneda@gmail.com","nancyp.juncol@uqvirtual.edu.co","nasa2101@gmail.com","bibliotecaelcarmen@hotmail.com","narty.vasquez@cnmh.gov.co","jornatan1317@gmail.com","jnjabonerol@unal.edu.co","leidyypiza1998@gmail.com","bibliolenguajeuniversal@gmail.com","bibliotecas.inderputumayo@gmail.com","nellybaba2016@gmail.com","bibliotecaelcocuy@gmail.com","marianat507@hotmail.com","netotru@yahoo.es","nellymmolina@gmail.com","nedan_27@hotmail.com","nefernando@hotmail.com","nzerpi27@gmail.com","nestor.solano@imct.gov.co","lorenar48ramirez@gmail.com","edithseka0930@gmail.com","soledacuarta@gmail.com","ngonzalez@bibliotecanacional.gov.co","noeme845@hotmail.com","nohara033@gmail.com","nvisbalr@gmail.com","nora.lucia.gomez@correounivalle.edu.co","ofeliabiblioteca1975@gmail.com","olenka.senmache@gmail.com","neido0621@hotmail.com","omedina.scr@gmail.com","ostam35@hotmail.com","paholoanga@hotmail.com","paomedinaguti97@gmail.com","andreacalderon0701@gmail.com","paolavizcaino3018@gmail.com","tutorpochoa@bibliotecanacional.gov.co","paolaisabelroa@gmail.com","patycan@gmail.com","bibliotecavijes@gmail.com","perezpreciado1012@gmail.com","paulabetancur04@gmail.com","paulavasquezg.06@gmail.com","pedro.hernandez10@sincelejoaprende.edu.co","pedrogjimenezh@gmail.com","tutorpguiral@bibliotecanacional.gov.co","petronamachado1966@gmail.com","piedado@yahoo.com","polmyrc@gmail.com","raiza.zabala.montoya@gmail.com","rennygranda@gmail.com","ricardo.osorio@ucacue.edu.ec","rinuye1791@hotmail.com","bpcomunitariapabloneruda2022@gmail.com","marimauri2006@gmail.com","roalroro_99@hotmail.com","bpgermanarciniegasdolores@gmail.com","romina-ruizdiaz@hotmail.com","piraquirosber1708@gmail.com","pecam1983@gmail.com","bibliotecamunicipal@charala-santander.gov.co","rosaconsuelo22@yahoo.es","rosita.671@hotmail.com","rcheve19@gmail.com","rrodriguez.utbanrep@gmail.com","warmity@gmail.com","rosalbautista@hotmail.com","rosanapaba313@hotmail.com","subdireccionbibliotecas@clena.org","rudark11@gmail.com","rubycarabali@hotmail.com","ambarpereira333@gmail.com","cultura@cucaita-boyaca.gov.co","samuel.ramos1632@gmail.com","samsarafundacione@yahoo.com","bibliotecaalfonsopalaciorudas@gmail.com","sandrarpnd1@gmail.com","smilena.montoya@udea.edu.co","unidosgranadasandram@gmail.com","milenammmejia@gmail.com","toli2975@hotmail.com","samiru00082@gmail.com","bibliotecapublica@nemocon-cundinamarca.gov.co","sandrapbohorquezc@gmail.com","bibliotecasucrecauca@gmail.com","liandy2709@gmail.com","sanp7276@gmail.com","ssuescun@bibliotecanacional.gov.co","manuzambrano10@yahoo.es","sandrajanet.ulloat@gmail.com","julieth2086@gmail.com","ciinterculturalidad@gmail.com","srios@bibliotecanacional.gov.co","saraulloag@gmail.com","sarahamerika16@gmail.com","bpflacienciadelsaber@gmail.com","sebastianmartinez0304@gmail.com","saavedra2016@gmail.com","rendonsebastian32@gmail.com","sebraca959@hotmail.com","sergioortizred@gmail.com","serman2013@hotmail.es","shgarzon@javeriana.edu.co","shirlyborrero@gmail.com","sdprieto@bibliotecanacional.gov.co","biblioteca@aratoca-santander.gov.co","kalibaag@gmail.com","sonyarocio@gmail.com","souldes@icloud.com","stephaguzman28@gmail.com","sugeyrovi@gmail.com","biblioteca@fuentedeoro-meta.gov.co","smorab@bibliotecanacional.gov.co","sujutaca@hotmail.com","taniamildredmoscoso@gmail.com","tlopeze@comfamiliar.edu.co","teresacausilramos@gmail.com","teines1604@gmail.com","tere.samo@hotmail.com","bocotaubisiu72@gmail.com","uritsacbe@ymail.com","vpantoja353@gmail.com","vtrillosm.03@gmail.com","valeriaflorez10@hotmail.com","vale121089@gmail.com","lvanessita@gmail.com","vera.vitola@cordoba.gov.co","valegria@minam.gob.pe","majo_2519@hotmail.com","fer33adal@gmail.com","vimantier@outlook.com","victor.munoz@correounivalle.edu.co","bibliotecapublicapuerres@gmail.com","biblioteca@muzo-boyaca.gov.co","vivis_910102@hotmail.com","proadministrativo@biloamigos.org","weduardov2005@gmail.com","pilicardenas97@gmail.com","bibpublica.cabrera@gmail.com","yaky0477@gmail.com","biblioteca@orito-putumayo.gov.co","yamibarre@hotmail.com","bibliotecaventaquemadaboyaca@gmail.com","yamile_0708@hotmail.com","yamili@ratondebiblioteca.org","yanethozuna22@hotmail.com","olaya.yaneth.ccb@gmail.com","aguileracristina020@gmail.com","carovaro@gmail.com","yeimygutierrezh@hotmail.com","kronoxy@hotmail.com","yebamos@gmail.com","yennymelo1@gmail.com","zulyney310@hotmail.es","biblioteca@chivata-boyaca.gov.co","yesenia2garizado@gmail.com","bibliotecamunicipal@armenia.gov.co","yazzminn-98@hotmail.com","yylancherosc@gmail.com","yesminbarrios76@gmail.com","yesnichanchi@gmail.com","yessicaagredo@unisangil.edu.co","mybibliotecapublica@gmail.com","yilma0123@gmail.com","yiselvalentinalepe@ufps.edu.co","yisneli.fuentes@cordoba.gov.co","biblioteca@santabarbara-santander.gov.co","yohan016dj@hotmail.com","yohanacogaria@hotmail.com","yubitzatellez702@gmail.com","yudyascaqui@gmail.com","bibliotecapublicapuli@gmail.com","navarroyulieth890@gmail.com","educativas.epcgranada@inpec.gov.co","yunesaca2910@gmail.com","bibliotecaisnos@hotmail.com","ymuriell@ut.edu.co","yuri.arevalo26@hotmail.com","yuriangelica1@hotmail.com","biblioteca@santamaria-boyaca.gov.co","yosoyzjf@gmail.com","zulmanieto99@gmail.com","biblioteca@sotaquira-boyaca.gov.co","pablo.pedroza@udea.edu.co","anaangelachiesa@hotmail.com","dm.bohorquezr@gmail.com"];
        
        
        foreach($users as $user ) {
           
            try {

                    $control = false;
                    foreach($emails as $email){
                        if(strtoupper($email) ==strtoupper( $user->email)) {
                            $control = true;
                        }
                    }
                    
                    if( $control && $user->notify_2 <= 1) { 
                        Notification::route('mail', $user->email)
                        ->notify(new DynamicNotification($fair, $subject, $title));
                        $user->notify_2 = 2;
                        $user->save();
                    }       
                
            } catch (\Throwable $th) {
                $user->notify_2 = -1;
                $user->save();
                return [
                    'success' => 500, 
                    'arrayUserMin' => $user,
                    'users' => $users
                ];
            }
           
          }
        
        return [
            'success' => 201,
            'users' => $users
        ];
        
        return true;
    } 

}
