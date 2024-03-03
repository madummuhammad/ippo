<script>
import { Head,useForm } from '@inertiajs/vue3';
import Layout from "@/Layouts/main.vue";
import PageHeader from "@/Components/page-header.vue";
import { ref, watch } from "vue";
import Multiselect from "@vueform/multiselect";
import "@vueform/multiselect/themes/default.css";

import CKEditor from "@ckeditor/ckeditor5-vue";
import ClassicEditor from "@ckeditor/ckeditor5-build-classic";
import DropZone from "@/Components/widgets/dropZone.vue";
import useVuelidate from "@vuelidate/core";

import flatPickr from "vue-flatpickr-component";
import "flatpickr/dist/flatpickr.css";

export default {
  setup() {
    let outletForm=ref(useForm({
      name:null,
      pic:null,
      image:null,
      address:null
    }))

    const selectedFile = async (event) => {
      try {
        const file = event.target.files[0];
        const formData = new FormData();
        formData.append('image', file);
        const response = await axios.post('/api/storage/upload', formData);
        const data = response.data;

        if(data.status=='success'){
          outletForm.value.image=data.data;
        }
      } catch (error) {
        console.error('error', error);
      }
    };

    let userData=ref([]);
    let userOption=ref([]);
    const getUser=async ()=> {
      try {
        const response = await axios.get('/api/user/list');
        const data = response.data;

        console.log('list',data);
        userData.value=data.data;

        data.data.forEach((item,index)=>{
          userOption.value[index]={
            value:item.id,
            label:item.name
          }
        })

        console.log('userOption',userOption.value)

      } catch (error) {
        console.error('error', error);
      }
    }

    getUser();

    const submit = () => {
      console.log(outletForm.value)
      outletForm.value.transform(data => ({
          ...data,
      })).post(route('outlet.store'), {
          onSuccess: (response) => {
              outletForm.value.reset()
          }
      });
    };

    return {
      selectedFile,
      outletForm,
      userData,
      userOption,
      submit
    }
  },
  data() {
    return {
    };
  },
  methods: {
  },
  components: {
    DropZone,
    Layout,
    PageHeader,
    ckeditor: CKEditor.component,
    Multiselect,
    flatPickr,
    Head
  },
};
</script>

<template>
  <Layout>
    <Head title="Create Project" />
    <PageHeader title="Create Project" pageTitle="Projects" />
    <BRow>
      <BCol lg="8">
        <BCard no-body>
          <BCardBody>
            <div class="mb-3">
              <label class="form-label" for="project-title-input">Nama</label>
              <input type="text" class="form-control" id="project-title-input" v-model="outletForm.name" :class="{'is-invalid':outletForm.errors.name}" placeholder="Masukan nama..." />
              <div class="invalid-feedback">
                {{outletForm.errors.name}}
              </div>
            </div>

            <BRow class="mb-3">
              <BCol lg="4">
                <div class="mb-3 mb-lg-0">
                  <label for="choices-priority-input" class="form-label">PIC</label>
                  <div class="form-control p-0"  :class="{'is-invalid':outletForm.errors.pic}" >
                    <Multiselect v-model="outletForm.pic"  :close-on-select="true" :options="userOption" :searchable="true"  />
                  </div>
                  <div class="invalid-feedback">
                    {{outletForm.errors.pic}}
                  </div>
                </div>
              </BCol>
            </BRow>

            <div class="mb-3">
              <label class="form-label" for="project-thumbnail-img">Gambar</label>
              <div class="col-3" v-if="outletForm.image">
                <img class="img-fluid mb-3" src="http://127.0.0.1:8000/storage/image/V3YuznK371gWqLcyPBvEYf8Fv37lJWn7hcrwSsj1.png">
              </div>
              <input @change="selectedFile" class="form-control"  :class="{'is-invalid':outletForm.errors.image}"  id="project-thumbnail-img" type="file"
                accept="image/png, image/gif, image/jpeg" />
                <div class="invalid-feedback">
                    {{outletForm.errors.image}}
                  </div>
            </div>

            <div class="mb-3">
              <label class="form-label">Alamat</label>
              <textarea  :class="{'is-invalid':outletForm.errors.address}"  v-model="outletForm.address"   class="form-control" rows="10"></textarea>
              <div class="invalid-feedback">
                {{outletForm.errors.address}}
              </div>
            </div>

          </BCardBody>
        </BCard>

        <div class="text-end mb-4">
          <!-- <BButton type="button" variant="danger" class="w-sm me-1">Back</BButton> -->
          <BButton type="submit" variant="success" class="w-sm" @click="submit">Create</BButton>
        </div>
      </BCol>
    </BRow>
  </Layout>
</template>
