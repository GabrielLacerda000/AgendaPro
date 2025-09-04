<script setup lang="ts">
import Heading from '@/components/Heading.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import AppLayout from '@/layouts/AppLayout.vue';
import { useForm } from '@inertiajs/vue3';      

const props = defineProps<{ user: any }>();

const form = useForm({
    speciality: props.user.speciality || '',
    description: props.user.description || '',
    location: props.user.location || '',
    price: props.user.price || '',
});

function submit() {
    form.patch(route('profile.update'));
}
</script>

<template>
    <AppLayout title="Professional Settings">
        <!-- <Heading>Professional Settings</Heading> -->

        <Card class="mt-6">
            <CardHeader>
                <CardTitle>Update Professional Information</CardTitle>
                <CardDescription>Update your professional information.</CardDescription>
            </CardHeader>
            <CardContent>
                <form @submit.prevent="submit">
                    <div class="grid gap-6">
                        <div class="grid gap-3">
                            <Label for="speciality">Speciality</Label>
                            <Input id="speciality" v-model="form.speciality" type="text" placeholder="e.g., Cardiologist, Dentist, Physiotherapist" />
                        </div>
                        <div class="grid gap-3">
                            <Label for="description">Description</Label>
                            <Textarea id="description" v-model="form.description" placeholder="Brief description about your practice and experience" rows="3" />
                        </div>
                        <div class="grid gap-3">
                            <Label for="location">Location</Label>
                            <Input id="location" v-model="form.location" type="text" placeholder="City, State or Full Address" />
                        </div>
                        <div class="grid gap-3">
                            <Label for="price">Price per consultation</Label>
                            <Input id="price" v-model="form.price" type="number" step="0.01" min="0" placeholder="0.00" />
                        </div>
                        <Button type="submit">Update</Button>
                    </div>
                </form>
            </CardContent>
        </Card>
    </AppLayout>
</template>