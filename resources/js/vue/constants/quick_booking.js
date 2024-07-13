export const SERVICE_LIST = ({ service_provider_id, employee_id }) => {
  return { path: `api/quick-booking/services-list?service_provider_id=${service_provider_id}&employee_id=${employee_id}`, method: 'GET' }
}
export const SERVICE_PROVIDER_LIST = ({ employee_id, service_id, start_date_time }) => {
  return { path: `api/quick-booking/service-provider-list?employee_id=${employee_id}&service_id=${service_id}&start_date_time=${start_date_time}`, method: 'GET' }
}
export const EMPLOYEE_LIST = ({ service_provider_id, service_id, start_date_time }) => {
  return { path: `api/quick-booking/employee-list?service_provider_id=${service_provider_id}&service_id=${service_id}&start_date_time=${start_date_time}`, method: 'GET' }
}
export const SLOT_TIME_LIST = ({ service_provider_id, date, service_id, employee_id }) => {
  return { path: `api/quick-booking/slot-time-list?service_provider_id=${service_provider_id}&date=${date}&employee_id=${employee_id}&service_id=${service_id}`, method: 'GET' }
}
export const STORE_URL = () => {
  return { path: `api/quick-booking/store`, method: 'POST' }
}
