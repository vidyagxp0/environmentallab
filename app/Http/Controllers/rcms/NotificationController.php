<?php

namespace App\Http\Controllers\rcms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{RiskManagement,
RiskAuditTrail,
CC,
RcmDocHistory,
Capa,
CapaAuditTrial,
RecordNumber,
Observation,
AuditTrialObservation,
Deviation,
DeviationAuditTrail,
AuditProgramAuditTrial,
AuditProgram,
ActionItem,
ActionItemHistory,
InternalAuditTrial,
Extension,
InternalAudit,
ExtensionAuditTrail,
EffectivenessCheck,
EffectivenessCheckAuditTrail,
RootCauseAnalysis,
RootAuditTrial,
LabIncidentAuditTrial,
LabIncident,
Auditee,
AuditTrialExternal,
ManagementReview,
ManagementAuditTrial,
User
};

class NotificationController extends Controller
{
    public function notificationDetail($slug, $id){
        switch ($slug) {
            case 'RiskAssessment':
                $notification = RiskAuditTrail::find($id);
                if($notification){
                    $riskAssessmentId = $notification->risk_id;
                    $parentData = RiskManagement::where('id', $riskAssessmentId)->first();

                    $userId = explode(',', $notification->mailUserId);
                    $getName = User::whereIn('id', $userId)->get(['name', 'email']);
                    return view('frontend.notification.notification_detail', compact('notification', 'getName', 'parentData'));
                }
                break;
            
            case 'LabIncident':
                $notification = LabIncidentAuditTrial::find($id);
                if($notification){
                    $labIncidentId = $notification->LabIncident_id;
                    $parentData = LabIncident::where('id', $labIncidentId)->first();

                    $userId = explode(',', $notification->mailUserId);
                    $getName = User::whereIn('id', $userId)->get(['name', 'email']);
                    return view('frontend.notification.notification_detail', compact('notification', 'getName', 'parentData'));
                }
                break;

            
            case 'ExternalAudit':
                $notification = AuditTrialExternal::find($id);
                if($notification){
                    $externalId = $notification->ExternalAudit_id;
                    $parentData = Auditee::where('id', $externalId)->first();

                    $userId = explode(',', $notification->mailUserId);
                    $getName = User::whereIn('id', $userId)->get(['name', 'email']);
                    return view('frontend.notification.notification_detail', compact('notification', 'getName', 'parentData'));
                }
                break;
            
            case 'AuditProgram':
                $notification = AuditProgramAuditTrial::find($id);
                if($notification){
                    $AuditProgramId = $notification->AuditProgram_id;
                    $parentData = AuditProgram::where('id', $AuditProgramId)->first();
        
                    $userId = explode(',', $notification->mailUserId);
                    $getName = User::whereIn('id', $userId)->get(['name', 'email']);
                    return view('frontend.notification.notification_detail', compact('notification', 'getName', 'parentData'));
                }
                break; 
                


            case 'ManagementReview':
                $notification = ManagementAuditTrial::find($id);
                if($notification){
                    $managementId = $notification->ManagementReview_id;
                    $parentData = ManagementReview::where('id', $managementId)->first();

                    $userId = explode(',', $notification->mailUserId);
                    $getName = User::whereIn('id', $userId)->get(['name', 'email']);
                    return view('frontend.notification.notification_detail', compact('notification', 'getName', 'parentData'));
                }
                break;


            case 'ChangeControl':
                $notification = RcmDocHistory::find($id);
                if($notification){
                    $changeControlId = $notification->cc_id;
                    $parentData = CC::where('id', $changeControlId)->first();

                    $userId = explode(',', $notification->mailUserId);
                    $getName = User::whereIn('id', $userId)->get(['name', 'email']);
                    return view('frontend.notification.notification_detail', compact('notification', 'getName', 'parentData'));
                }
                break;

            case 'InternalAudit':
                    $notification = InternalAuditTrial::find($id);
                    if($notification){
                        $InternalAuditId = $notification->InternalAudit_id;
                        $parentData = InternalAudit::where('id', $InternalAuditId)->first();
    
                        $userId = explode(',', $notification->mailUserId);
                        $getName = User::whereIn('id', $userId)->get(['name', 'email']);
                        return view('frontend.notification.notification_detail', compact('notification', 'getName', 'parentData'));
                    }
                    break;    


            case 'CAPA':
                $notification = CapaAuditTrial::find($id);
                if($notification){
                    $CapaId = $notification->capa_id;
                    $parentData = Capa::where('id', $CapaId)->first();

                    $userId = explode(',', $notification->mailUserId);
                    $getName = User::whereIn('id', $userId)->get(['name', 'email']);
                    return view('frontend.notification.notification_detail', compact('notification', 'getName', 'parentData'));
                }
                break;


            case 'Observation':
                $notification = AuditTrialObservation::find($id);
                if($notification){
                    $observationId = $notification->Observation_id;
                    $parentData = Observation::where('id', $observationId)->first();

                    $userId = explode(',', $notification->mailUserId);
                    $getName = User::whereIn('id', $userId)->get(['name', 'email']);
                    return view('frontend.notification.notification_detail', compact('notification', 'getName', 'parentData'));
                }
                break;


            case 'Deviation':
                $notification = DeviationAuditTrail::find($id);
                if($notification){
                    $deviationId = $notification->deviation_id;
                    $parentData = Deviation::where('id', $deviationId)->first();

                    $userId = explode(',', $notification->mailUserId);
                    $getName = User::whereIn('id', $userId)->get(['name', 'email']);
                    return view('frontend.notification.notification_detail', compact('notification', 'getName', 'parentData'));
                }
                break;


            case 'ActionItem':
                $notification = ActionItemHistory::find($id);
                if($notification){
                    $actionItemId = $notification->cc_id;
                    $parentData = ActionItem::where('id', $actionItemId)->first();

                    $userId = explode(',', $notification->mailUserId);
                    $getName = User::whereIn('id', $userId)->get(['name', 'email']);
                    return view('frontend.notification.notification_detail', compact('notification', 'getName', 'parentData'));
                }
                break;


            case 'Extension':
                $notification = ExtensionAuditTrail::find($id);
                if($notification){
                    $extensionId = $notification->extension_id;
                    $parentData = Extension::where('id', $extensionId)->first();

                    $userId = explode(',', $notification->mailUserId);
                    $getName = User::whereIn('id', $userId)->get(['name', 'email']);
                    return view('frontend.notification.notification_detail', compact('notification', 'getName', 'parentData'));
                }
                break;


            case 'EffectivenessCheck':
                $notification = EffectivenessCheckAuditTrail::find($id);
                if($notification){
                    $effectivenessCheckId = $notification->effectiveness_id;
                    $parentData = EffectivenessCheck::where('id', $effectivenessCheckId)->first();

                    $userId = explode(',', $notification->mailUserId);
                    $getName = User::whereIn('id', $userId)->get(['name', 'email']);
                    return view('frontend.notification.notification_detail', compact('notification', 'getName', 'parentData'));
                }
                break;

            case 'RCA':
                $notification = RootAuditTrial::find($id);
                if($notification){
                    $rootCauseId = $notification->root_id;
                    $parentData = RootCauseAnalysis::where('id', $rootCauseId)->first();

                    $userId = explode(',', $notification->mailUserId);
                    $getName = User::whereIn('id', $userId)->get(['name', 'email']);
                    return view('frontend.notification.notification_detail', compact('notification', 'getName', 'parentData'));
                }
                break;


            default:
                return $slug;
                break;
        }
    }
}
