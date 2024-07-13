
export const GET_SERVICE_LIST = ({encounter_id}) => {return {path: `clinic-services/index_list?encounter_id=${encounter_id}`, method: 'GET'}}

export const GET_SERVICE_DETAILS = ({encounter_id,service_id}) => {return {path: `clinic-services/service-details?service_id=${service_id}&encounter_id=${encounter_id}`, method: 'GET'}}

export const SAVE_BILLING_DETAILS = () => {return {path: `billing-record/save-billing-details`, method: 'POST'}}
