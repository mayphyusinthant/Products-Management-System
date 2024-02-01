<script setup>
    import AppLayout from '@/Layouts/AppLayout.vue';
    import SectionBorder from '@/Components/SectionBorder.vue';
    import Welcome from '@/Components/Welcome.vue';
    import {Link, Head} from '@inertiajs/vue3';
    import { defineProps, ref , onMounted} from 'vue';
    import { router } from '@inertiajs/vue3'  
    import JetLabel from "@/Components/InputLabel.vue";
    import JetInputError from "@/Components/InputError.vue";
    import TextInput from "@/Components/TextInput.vue";
    import JetActionMessage from "@/Components/ActionMessage.vue";
    import { useForm } from '@inertiajs/vue3';

    const errors = ref(null);
    const props = defineProps(['edit', 'item' ]);
    console.log('edit:', props.edit);
    console.log('item:', props.item);
    
    const components = {
    JetLabel,
    JetInputError,
    TextInput,
    JetActionMessage
  };


  const formData = useForm({
    name: '',
    description: '',
    artType: '',
    size: '',
    medium: '',
    price: '',
    status: '',
    img: '',
  });

  const formErrors = ref({
    name: null,
    description: null,
    artType: null,
    canvasSize: null,
    medium: null,
    price: null,
    status: null,
    img: null,

    // Add other form fields errors here
  });

const saveItem = () => {
  console.log(formData);

  if(props.edit === true) {
    formData.put(route('items.update', props.item['id']), {
    // Adjust the data fields based on your form structure
    name: formData.data.name,
    description: formData.data.description,
    artType: formData.data.artType,
    size: formData.data.size,
    medium: formData.data.medium,
    price: formData.data.price,
    status: formData.data.status,
    img: formData.data.img,

  }, {
    onSuccess: () => {
      // Handle success if needed
    },
    onError: (errors) => {
      // Handle errors if needed
    },
  });

  }
  else {

    formData.post(route('items.store'), {
    // Adjust the data fields based on your form structure
    name: formData.data.name,
    description: formData.data.description,
    artType: formData.data.artType,
    size: formData.data.size,
    medium: formData.data.medium,
    price: formData.data.price,
    status: formData.data.status,
    img: formData.data.img,

  }, {
    onSuccess: () => {
      // Handle success if needed
    },
    onError: (errors) => {
      // Handle errors if needed
    },
  });

  }
};


  
onMounted(() => {
  // Set the initial value of description based on edit mode
  if (props.edit && props.item) {
    formData.name = props.item['name'];
    formData.description = props.item['description'];
    formData.artType = props.item['artType'];
    formData.size = props.item['size'];
    formData.price = props.item['price'].toString();
    formData.medium = props.item['medium'];
    formData.status = props.item['status'];
  }

});

    import { getCookie } from '@/utils'; // Adjust the path based on your project structure

    let darkMode = null;

    onMounted(() => {
      if(getCookie('darkMode') == 'on'){
        darkMode = true;
      }else{
        darkMode = false;
      }

      console.log(darkMode)
    });
</script>

<!-- resources/js/Pages/Items/Create.vue -->
<template #header>
    <AppLayout title="Item">
        <template #header>
            <h1 class="font-semibold custom-text-xl  leading-tight">
                <Link :href="route('items.index')" class = "text-pink-900"  :class="{ 'dark:text-gray-800': darkMode }">Items</Link>
                <span class="text-gray-600"> {{ edit ? '/ Edit' : '/ Create' }} </span>
            </h1>
            <div class="py-12" >
                <div class="max-w-7xl mx-auto p-5 sm:px-6 lg:px-8 bg-white overflow-hidden shadow-xl sm:rounded-lg" :class="{ 'dark:bg-gray-800': darkMode }">
                    <div v-if="errors" class="alert alert-danger">
                        <ul>
                            <li v-for="(error, key) in errors" :key="key">{{ error[0] }}</li>
                        </ul>
                    </div>

                    <form class="w-full max-w-lg flex flex-wrap" @submit.prevent="saveItem" >
                        
                        <!-- Item Name -->
                        <div class="mb-6 w-full md:w-1/2 ">
                            <JetLabel for="name" >Item Name</JetLabel>
                            <TextInput  id= "name" type="text" placeholder="Enter item name"                      
                            :class="{ 'dark:bg-gray-800': darkMode, 'focus:border-none': darkMode, 'focus:ring-white': darkMode }"
                            v-model="formData.name"></TextInput>
                            <JetInputError :message="formData.errors.name" />

                          </div>

                      
                        <!-- Art Type -->
                        <div class="mb-6 md:w-1/2 ">
                            <JetLabel for="artType" >Art Type</JetLabel>
                            <TextInput id="artType" type="text" placeholder="Enter art type" 
                            :class="{ 'dark:bg-gray-800': darkMode, 'focus:border-none': darkMode, 'focus:ring-white': darkMode }"
                            v-model="formData.artType"></TextInput>
                            <JetInputError :message="formData.errors.artType" />

                          </div>


                        <!-- Description Textarea -->
                        <div class="mb-6 w-full ">
                          <JetLabel for="description" >Description</JetLabel>
                          <textarea id="description" placeholder="Enter item description" 
                          class="form-textarea w-full  border-gray-300 focus:border-pink-400 focus:ring-pink-400 rounded-md shadow-sm" 
                          :class="{ 'dark:bg-gray-800': darkMode, 'focus:border-none': darkMode, 'focus:ring-white': darkMode }"
                           v-model="formData.description" rows = "4"> </textarea>
                          <JetInputError :message="formData.errors.description" />

                        </div>
                      
                        
                      
                        <!-- Canvas Size -->
                        <div class="mb-6 md:w-1/2">
                          <JetLabel for="size" >Canvas Size</JetLabel>
                          <TextInput  id="size" type="text" placeholder="Enter canvas size"
                          :class="{ 'dark:bg-gray-800': darkMode, 'focus:border-none': darkMode, 'focus:ring-white': darkMode }"
                          v-model="formData.size"></TextInput>
                          <JetInputError :message="formData.errors.size" />

                        </div>
                      
                        <!-- Medium Dropdown -->
                        <div class="mb-6 md:w-1/2">
                          <JetLabel for="medium" >Medium</JetLabel>
                          <select id="medium" class = " border-gray-300 focus:border-pink-400 focus:ring-pink-400 rounded-md shadow-sm"
                          :class="{ 'dark:bg-gray-800': darkMode, 'focus:border-none': darkMode, 'focus:ring-white': darkMode, 'dark:text-white': darkMode  }" v-model="formData.medium">
                            <option>Oil</option>
                            <option>Acrylic</option>
                            <option>Water Color</option>
                            <!-- Add other medium options as needed -->
                          </select>
                          <JetInputError :message="formData.errors.medium" />

                        </div>
                      
                        <!-- Price -->
                        <div class="mb-6 md:w-1/2">
                          <JetLabel for="price" >Price</JetLabel>
                          <TextInput id="price" type="number" placeholder="Enter price"
                          :class="{ 'dark:bg-gray-800': darkMode, 'focus:border-none': darkMode, 'focus:ring-white': darkMode }"
                          class="form-input w-full" v-model="formData.price"></TextInput>
                          <JetInputError :message="formData.errors.price" />

                        </div>
                      
                        <!-- Status Dropdown -->
                        <div class="mb-6 md:w-1/2">
                          <JetLabel for="status" >Status</JetLabel>
                          <select id="status" class="form-select w-full  border-gray-300 focus:border-pink-400 focus:ring-pink-400 rounded-md shadow-sm"
                          :class="{ 'dark:bg-gray-800': darkMode, 'focus:border-none': darkMode, 'focus:ring-white': darkMode, 'dark:text-white': darkMode  }" v-model="formData.status">
                            <option>Available</option>
                            <option>Sold Out</option>
                            <option>Not For Sale</option>

                          </select>
                          <JetInputError :message="formData.errors.status" />

                        </div>
                      
                        <!-- Image Upload Drag and Drop Input -->
                        <div class="mb-12 w-full">
                          <JetLabel for="img" >Image Upload</JetLabel>
                          <TextInput id="img"  type="file" class="form-input w-full" 
                          :class="{ 'dark:bg-gray-800': darkMode, 'focus:border-none': darkMode, 'focus:ring-white': darkMode }"
                          v-model="formData.img"></TextInput>
                          <JetInputError :message="formData.errors.img" />

                          <p class="text-gray-600 text-xs italic">Drag and drop or click to upload an image</p>
                        </div>
                        

                        <!-- Submit Button -->
                        <div class="w-full flex md:flex justify-end"> <!-- Add justify-end here to align the button to the right -->
                            <button  class="bg-gray-700 ml-1  text-white py-2 px-4 rounded hover:bg-gray-500 focus:shadow-outline focus:outline-none">
                              <Link :href="route('items.index')" >Back</Link> </button> 
                            <button type="submit"   :class="{ 'dark:bg-gray-900': darkMode}"
                            class="bg-pink-900 ml-1 text-white py-2 px-4 rounded hover:bg-pink-950 focus:shadow-outline focus:outline-none">
                              {{ edit ? ' Update' : 'Create Item' }} </button>
                        </div>
                      </form>
                  </div>
            </div>
        </template>
        
        
          
    </AppLayout>
</template>

