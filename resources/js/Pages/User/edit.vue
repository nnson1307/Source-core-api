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
    props: {
        infoUser: Object
    },
    data() {
        return {
            errors: null,
            isShowError: false,
            isShowSuccess: false,
        }
    },
    computed: {
        loadUser() {
            return this.infoUser
        },
    },
    watch: {

    },
    methods: {
        save() {
            axios({
                method: 'PUT',
                url: '/api/users/' + this.infoUser.id,
                data: {
                    name: this.infoUser.name,
                    email: this.infoUser.email,
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

                    this.errors = messageError;
                    this.isShowError = true;
                    this.isShowSuccess = false;
                } else {
                    //Save user success
                    this.isShowSuccess = true;
                    this.isShowError = false;

                    //Trigger event load lại ds user từ component cha
                    setTimeout(() => this.$emit("triggerUpdate", {
                        isUpdateListUser: true,
                        isEdit: false
                    }), 1000);
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
            <h2 class="text-lg font-medium text-gray-900">Edit User</h2>
        </header>
    </div>

    <div class="alert alert-danger alert-dismissible fade show" v-show="isShowError" role="alert">
        <p v-html="errors"></p>

    </div>

    <div class="alert alert-success alert-dismissible fade show" v-show="isShowSuccess" role="alert">
        <p>Edit success.</p>
    </div>

    <div class="form-group">
        <Label name="User Name"></Label>
        <TextInput type="text" id="name" placeholder="Enter user name" v-model="this.infoUser.name"></TextInput>
    </div>
    <div class="form-group">
        <Label name="Email"></Label>
        <TextInput type="text" id="email" placeholder="Enter email" v-model="this.infoUser.email"></TextInput>
    </div>

    <Button type="button" name="Saved" class="btn btn-success" style="background-color: #28a745 !important;"
        @click="save"></Button>
</template>
