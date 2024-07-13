<template>
   
    <form @submit="formSubmit" class="">
        <div class="modal fade" id="Billing_Modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                      <template v-if="currentId != 0">
                        <span>{{ $t('clinic.generate_invoice') }}</span>
                      </template>
                      <template v-else>
                        <span>{{ $t('clinic.generate_invoice') }}</span>
                      </template>
                     
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row" id="form-offcanvas">
                      
                              <div class="form-group">
                                <label class="form-label col-md-12">{{ $t('clinic.lbl_select_service') }} <span class="text-danger">*</span></label>
                                <Multiselect id="service_id" v-model="service_id" :value="service_id" placeholder="Select Service" v-bind="singleSelectOption" :options="services.options" @select="selectService" class="form-group"></Multiselect>
                                <span v-if="errorMessages['service_id']">
                                  <ul class="text-danger">
                                    <li v-for="err in errorMessages['service_id']" :key="err">{{ err }}</li>
                                  </ul>
                                </span>
                                <span class="text-danger">{{ errors['service_id'] }}</span>
                             
                              </div>

                              <div class="row">
                                <div class="col-md-12 table-responsive">
                                  <table class="table table-sm text-center table-bordered custom-table">
                                    <thead class="thead-light">
                                      <tr>
                                         <th>Services</th> 
                                         <th>Price</th> 
                                         <th>Total</th> 
                                        </tr>
                                      </thead> 
                                      <tbody>
                                        <tr v-if="ServiceDetails !=null">
                                          <td>{{ServiceDetails.name}}</td>
                                          <td v-if="ServiceDetails.service_price_data">{{formatCurrencyVue(ServiceDetails.service_price_data.service_price)}}</td>
                                          <td v-else>{{formatCurrencyVue(0)}}</td>

                                          <td v-if="ServiceDetails.service_price_data">{{formatCurrencyVue(ServiceDetails.service_price_data.total_amount)}}</td>
                                          <td v-else>{{formatCurrencyVue(0)}}</td>

                                        </tr>
                                        <tr v-else>
                                          <td colspan="3">
                                            <span class="text-primary mb-0">No Data found</span>
                                          </td>
                                        </tr>
                                      </tbody>
                                    </table> 
                                  </div>
                                </div>
                             </div>


                            <div class="row">
                            <div class="col-md-12 table-responsive">
                              <table class="table table-sm text-center table-bordered custom-table">
                                <thead class="thead-light">
                                  <tr>
                                    <th>Sr no</th>
                                     <th>Tax Name</th> 
                                     <th>Charges</th>
                                    </tr>
                                  </thead> 
                                  <tbody>
                                    <tr  v-for="(tax, index) in taxData" :key="index" >
                                      <td>{{index+1}}</td>
                                       <td v-if="tax.type=='fixed'">{{tax.title}} ( {{formatCurrencyVue(tax.value)}} )</td>
                                        <td v-else >{{tax.title}} ( {{tax.value}} % )</td>
                                        <td>{{formatCurrencyVue(tax.amount)}}</td>
                                    </tr>
                                    <tr v-if="taxData.length==0">
                                      <td colspan="3">
                                        <span class="text-primary mb-0">No tax found</span>
                                      </td>
                                    </tr>
                                  </tbody>
                                </table>
                              </div>
                            </div>

                               <div class="row">
                                <div class="col-md-12 table-responsive">

                                  <div class="d-flex"><h6> Service Price :</h6><h6 v-if="ServiceDetails !=null"> {{formatCurrencyVue(ServiceDetails.service_price_data.service_price)}}</h6> <h6 v-else>{{formatCurrencyVue(0)}}</h6></div>
                                  <div class="d-flex"><h6> Discount Amount :</h6> <h6 v-if="ServiceDetails !=null"> {{formatCurrencyVue(ServiceDetails.service_price_data.discount_amount)}} </h6> <h6 v-else>{{formatCurrencyVue(0)}}</h6></div>
                                  <div class="d-flex"><h6> Total Payable Amount with Tax:</h6> <h6 v-if="ServiceDetails !=null"> {{formatCurrencyVue(ServiceDetails.service_price_data.total_amount)}} </h6><h6 v-else>{{formatCurrencyVue(0)}}</h6></div>

                                </div>
                            </div> 

                             <div class="col-md-12">
                                <label class="form-label">{{ $t('clinic.lbl_payment_status') }}</label>
                                <Multiselect id="payment_status" v-model="payment_status" :value="payment_status" v-bind="payment_status_data" class="form-group"></Multiselect>
                                <span class="text-danger">{{ errors.payment_status }}</span>
                           </div>


                     </div>
                    <div class="float-left"><h4 class="text-center small text-danger">Note: To close the encounter, invoice payment is mandatory</h4></div>
                    <div class="modal-footer">
                      <button type="submit" class="btn btn-primary">Save</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</template>
<script setup>
import { ref, watch, onMounted } from 'vue'

import { useRequest } from '@/helpers/hooks/useCrudOpration'
import { buildMultiSelectObject } from '@/helpers/utilities'
import { useField, useForm } from 'vee-validate'
import * as yup from 'yup'
import InputField from '@/vue/components/form-elements/InputField.vue'
import { GET_SERVICE_LIST ,GET_SERVICE_DETAILS, SAVE_BILLING_DETAILS} from '@/vue/constants/envoice'


const CURRENCY_SYMBOL = ref(window.defaultCurrencySymbol)


const emit = defineEmits(['submit'])

const props = defineProps({
  encounter_id: { type: Number, default: '' },
  user_id: { type: Number, default: '' },
  id: { type: Number, default: 0 }
  
})

const formatCurrencyVue = window.currencyFormat

const { storeRequest,listingRequest,getRequest,updateRequest} = useRequest()

const currentId = ref(props.id)
// Edit Form Or Create Form
watch(

  () => props.id,

  (value) => {
    currentId.value = value

    if (value > 0) {
      getRequest({ url: EDIT_PRESCRIPTION_URL, id: value }).then((res) => {
        if (res.status && res.data) {
          setFormData(res.data)
        }
      })
    } else {
    
      setFormData(defaultData())
      
      getServiceList()
    }
    
    getServiceList()
  
  }
 

)

const singleSelectOption = ref({
  closeOnSelect: true,
  searchable: true,
})



/*
 * Form Data & Validation & Handeling
 */
// Default FORM DATA
const defaultData = () => {
  errorMessages.value = {}
  return {
    service_id: '',
    payment_status: 0,

  }
}

//  Reset Form
const setFormData = (data) => {
  resetForm({
    values: {
      service_id: data.service_id,
      payment_status: data.payment_status,
    
    }
  })
}

// Validations
const validationSchema = yup.object({
    service_id: yup.string().required('Please Select Service'),
    payment_status: yup.string().required('Please Select payment'),
  
})

const { handleSubmit, errors, resetForm } = useForm({
  validationSchema
})

const { value: service_id } = useField('service_id')
const { value: payment_status } = useField('payment_status')

const errorMessages = ref({})

onMounted(() => {
  setFormData(defaultData())
  
})


const services = ref({ options: [], list: [] })

const getServiceList = () => {
    
   listingRequest({ url: GET_SERVICE_LIST, data: {encounter_id:props.encounter_id}}).then((res) => {
    services.value.list=res
    services.value.options = buildMultiSelectObject(res, {
     value: 'id',
     label: 'name'
    })
  })
    
}

const ServiceDetails=ref(null);
const taxData=ref([]);

const selectService= (value)=>{

  listingRequest({ url: GET_SERVICE_DETAILS, data: {encounter_id:props.encounter_id, service_id:value}}).then((res) => {

    ServiceDetails.value=res.data;

    if(res.data){
        taxData.value=res.data.tax_data
    }
  
  })
}

const payment_status_data = ref({
  searchable: true,
  options: [
    { label: 'Pending', value: 0 },
    { label: 'Paid', value: 1 },
  ],
  closeOnSelect: true,

})


const reset_datatable_close_offcanvas = (res) => {
  
  if (res.status) {
    window.successSnackbar(res.message)
    bootstrap.Modal.getInstance('#Billing_Modal').hide()
    setFormData(defaultData())
    emit('onSubmit')

  } else {
    window.errorSnackbar(res.message)
    errorMessages.value = res.all_message
  }
}


const formSubmit = handleSubmit((values) => {

     values.user_id=props.user_id
     values.encounter_id=props.encounter_id
     values.service_details=ServiceDetails.value

  if (currentId.value > 0) {   
    
    updateRequest({ url: SAVE_BILLING_DETAILS, body: values ,id: currentId.value}).then((res) => {
    if(res.status) {
      emit('submit', {type: 'create_customer', value: res.prescription})
      setFormData(defaultData())
      bootstrap.Modal.getInstance(document.getElementById("exampleModal")).hide()
      } 
    })
    
    currentId.value=0;
  }else{

    storeRequest({ url: SAVE_BILLING_DETAILS, body: values }).then((res) => {
    if(res.status) {

      reset_datatable_close_offcanvas(res)
      
      } 
   })

  }

})



</script>
