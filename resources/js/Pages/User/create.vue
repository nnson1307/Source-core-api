<script>
import { Head, Link, useForm } from '@inertiajs/vue3';
import Label from '@/ComponentShare/label.vue';
import TextInput from '@/ComponentShare/text-input.vue';
import Button from '@/ComponentShare/button.vue';

export default {
    components: {
        Link,
        Head,
        Label,
        TextInput,
        Button
    },
    data() {
        return {
            errors: null,
            isShowError: false,
            isShowSuccess: false,
            user_name: null,
            email: null,
            password: null
        }
    },
    methods: {
        save() {
            axios({
                method: 'POST',
                url: '/api/users',
                data: {
                    name: this.user_name,
                    email: this.email,
                    password: this.password,
                }
            }).then(function (res) {
                if (res.data.status == 422) {
                    var messageError = '';

                    if (typeof res.data.erros.email !== 'undefined') {
                        messageError += res.data.erros.email[0] + '<br>';
                    }

                    if (typeof res.data.erros.name !== 'undefined') {
                        messageError += res.data.erros.name[0] + '<br>';
                    }

                    if (typeof res.data.erros.password !== 'undefined') {
                        messageError += res.data.erros.password[0] + '<br>';
                    }

                    this.errors = messageError;
                    this.isShowError = true;
                    this.isShowSuccess = false;
                } else {
                    //Save user success
                    this.isShowSuccess = true;
                    this.isShowError = false;

                    this.user_name = '';
                    this.email = '';
                    this.password = '';

                    //Trigger event load lại ds user từ component cha
                    this.$emit("triggerUser", true);
                }
            }.bind(this));
        }
    },
    mounted() {

    },
}

</script>

<template>
    <div class="form-group">
        <header>
            <h2 class="text-lg font-medium text-gray-900">Create User</h2>
        </header>
    </div>

    <div class="alert alert-danger alert-dismissible fade show" v-show="isShowError" role="alert">
        <p v-html="errors"></p>
    </div>

    <div class="alert alert-success alert-dismissible fade show" v-show="isShowSuccess" role="alert">
        <p>Create success.</p>
    </div>

    <div class="form-group">
        <Label name="User Name"></Label>
        <TextInput type="text" id="name" placeholder="Enter user name" v-model="user_name"></TextInput>
    </div>
    <div class="form-group">
        <Label name="Email"></Label>
        <TextInput type="text" id="email" placeholder="Enter email" v-model="email"></TextInput>
    </div>

    <div class="form-group">
        <Label name="Password"></Label>
        <TextInput type="password" id="password" placeholder="Enter password" v-model="password"></TextInput>
    </div>

    <Button type="button" name="Saved" class="btn btn-success" style="background-color: #28a745 !important;" @click="save"></Button>
</template>
