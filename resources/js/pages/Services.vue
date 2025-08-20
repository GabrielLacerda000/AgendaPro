<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Dialog, DialogContent, DialogDescription, DialogHeader, DialogTitle, DialogTrigger } from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { Head, router, useForm } from '@inertiajs/vue3';
import { Edit, Plus, Trash2 } from 'lucide-vue-next';
import { ref } from 'vue';

interface Service {
    id: number;
    name: string;
    description: string | null;
    duration: number;
    price: number;
    created_at: string;
    updated_at: string;
}

interface Props {
    services: Service[];
}

defineProps<Props>();

const isCreateDialogOpen = ref(false);
const isEditDialogOpen = ref(false);
const isDeleteDialogOpen = ref(false);
const selectedService = ref<Service | null>(null);

const createForm = useForm({
    name: '',
    description: '',
    duration: null as number | null,
    price: null as number | null,
});

const editForm = useForm({
    name: '',
    description: '',
    duration: null as number | null,
    price: null as number | null,
});

const formatPrice = (price: number) => {
    return new Intl.NumberFormat('pt-BR', {
        style: 'currency',
        currency: 'BRL',
    }).format(price);
};

const formatDuration = (duration: number) => {
    if (duration < 60) {
        return `${duration} min`;
    }
    const hours = Math.floor(duration / 60);
    const minutes = duration % 60;
    return minutes > 0 ? `${hours}h ${minutes}min` : `${hours}h`;
};

const openCreateDialog = () => {
    createForm.reset();
    isCreateDialogOpen.value = true;
};

const openEditDialog = (service: Service) => {
    selectedService.value = service;
    editForm.name = service.name;
    editForm.description = service.description || '';
    editForm.duration = service.duration;
    editForm.price = service.price;
    isEditDialogOpen.value = true;
};

const openDeleteDialog = (service: Service) => {
    selectedService.value = service;
    isDeleteDialogOpen.value = true;
};

const submitCreate = () => {
    createForm.post(route('services.store'), {
        onSuccess: () => {
            isCreateDialogOpen.value = false;
            createForm.reset();
        },
    });
};

const submitEdit = () => {
    if (!selectedService.value) return;
    
    editForm.put(route('services.update', selectedService.value.id), {
        onSuccess: () => {
            isEditDialogOpen.value = false;
            editForm.reset();
            selectedService.value = null;
        },
    });
};

const deleteService = () => {
    if (!selectedService.value) return;
    
    router.delete(route('services.destroy', selectedService.value.id), {
        onSuccess: () => {
            isDeleteDialogOpen.value = false;
            selectedService.value = null;
        },
    });
};
</script>

<template>
    <Head title="My Services" />

    <AppLayout :breadcrumbs="[{ title: 'My Services', href: '/services' }]">
        <div class="space-y-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold tracking-tight">My Services</h1>
                    <p class="text-muted-foreground">
                        Manage the services you offer to your patients.
                    </p>
                </div>
                <Dialog v-model:open="isCreateDialogOpen">
                    <DialogTrigger as-child>
                        <Button @click="openCreateDialog">
                            <Plus class="mr-2 h-4 w-4" />
                            Create Service
                        </Button>
                    </DialogTrigger>
                    <DialogContent class="sm:max-w-[425px]">
                        <DialogHeader>
                            <DialogTitle>Create New Service</DialogTitle>
                            <DialogDescription>
                                Add a new service that you offer to your patients.
                            </DialogDescription>
                        </DialogHeader>
                        <form @submit.prevent="submitCreate" class="space-y-4">
                            <div class="space-y-2">
                                <Label for="create-name">Service Name</Label>
                                <Input
                                    id="create-name"
                                    v-model="createForm.name"
                                    placeholder="e.g., Initial Consultation"
                                    required
                                />
                                <div v-if="createForm.errors.name" class="text-sm text-red-600">
                                    {{ createForm.errors.name }}
                                </div>
                            </div>
                            <div class="space-y-2">
                                <Label for="create-description">Description</Label>
                                <Textarea
                                    id="create-description"
                                    v-model="createForm.description"
                                    placeholder="Describe what this service includes..."
                                    rows="3"
                                />
                                <div v-if="createForm.errors.description" class="text-sm text-red-600">
                                    {{ createForm.errors.description }}
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="space-y-2">
                                    <Label for="create-duration">Duration (minutes)</Label>
                                    <Input
                                        id="create-duration"
                                        v-model="createForm.duration"
                                        type="number"
                                        min="1"
                                        max="480"
                                        placeholder="60"
                                        required
                                    />
                                    <div v-if="createForm.errors.duration" class="text-sm text-red-600">
                                        {{ createForm.errors.duration }}
                                    </div>
                                </div>
                                <div class="space-y-2">
                                    <Label for="create-price">Price (R$)</Label>
                                    <Input
                                        id="create-price"
                                        v-model="createForm.price"
                                        type="number"
                                        step="0.01"
                                        min="0"
                                        placeholder="150.00"
                                        required
                                    />
                                    <div v-if="createForm.errors.price" class="text-sm text-red-600">
                                        {{ createForm.errors.price }}
                                    </div>
                                </div>
                            </div>
                            <div class="flex justify-end space-x-2">
                                <Button type="button" variant="outline" @click="isCreateDialogOpen = false">
                                    Cancel
                                </Button>
                                <Button type="submit" :disabled="createForm.processing">
                                    Create Service
                                </Button>
                            </div>
                        </form>
                    </DialogContent>
                </Dialog>
            </div>

            <Card>
                <CardHeader>
                    <CardTitle>Services</CardTitle>
                    <CardDescription>
                        A list of all services you currently offer.
                    </CardDescription>
                </CardHeader>
                <CardContent>
                    <div v-if="services.length === 0" class="text-center py-8">
                        <p class="text-muted-foreground">No services found.</p>
                        <p class="text-sm text-muted-foreground">Create your first service to get started.</p>
                    </div>
                    <div v-else class="rounded-md border">
                        <Table>
                            <TableHeader>
                                <TableRow>
                                    <TableHead>Name</TableHead>
                                    <TableHead>Description</TableHead>
                                    <TableHead>Duration</TableHead>
                                    <TableHead>Price</TableHead>
                                    <TableHead class="text-right">Actions</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                <TableRow v-for="service in services" :key="service.id">
                                    <TableCell class="font-medium">{{ service.name }}</TableCell>
                                    <TableCell>
                                        <span v-if="service.description" class="text-sm">
                                            {{ service.description.length > 50 ? service.description.substring(0, 50) + '...' : service.description }}
                                        </span>
                                        <span v-else class="text-muted-foreground text-sm">No description</span>
                                    </TableCell>
                                    <TableCell>{{ formatDuration(service.duration) }}</TableCell>
                                    <TableCell>{{ formatPrice(service.price) }}</TableCell>
                                    <TableCell class="text-right">
                                        <div class="flex justify-end space-x-2">
                                            <Button
                                                variant="outline"
                                                size="sm"
                                                @click="openEditDialog(service)"
                                            >
                                                <Edit class="h-4 w-4" />
                                            </Button>
                                            <Button
                                                variant="destructive"
                                                size="sm"
                                                @click="openDeleteDialog(service)"
                                            >
                                                <Trash2 class="h-4 w-4" />
                                            </Button>
                                        </div>
                                    </TableCell>
                                </TableRow>
                            </TableBody>
                        </Table>
                    </div>
                </CardContent>
            </Card>
        </div>

        <!-- Edit Dialog -->
        <Dialog v-model:open="isEditDialogOpen">
            <DialogContent class="sm:max-w-[425px]">
                <DialogHeader>
                    <DialogTitle>Edit Service</DialogTitle>
                    <DialogDescription>
                        Update the details of your service.
                    </DialogDescription>
                </DialogHeader>
                <form @submit.prevent="submitEdit" class="space-y-4">
                    <div class="space-y-2">
                        <Label for="edit-name">Service Name</Label>
                        <Input
                            id="edit-name"
                            v-model="editForm.name"
                            placeholder="e.g., Initial Consultation"
                            required
                        />
                        <div v-if="editForm.errors.name" class="text-sm text-red-600">
                            {{ editForm.errors.name }}
                        </div>
                    </div>
                    <div class="space-y-2">
                        <Label for="edit-description">Description</Label>
                        <Textarea
                            id="edit-description"
                            v-model="editForm.description"
                            placeholder="Describe what this service includes..."
                            rows="3"
                        />
                        <div v-if="editForm.errors.description" class="text-sm text-red-600">
                            {{ editForm.errors.description }}
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <Label for="edit-duration">Duration (minutes)</Label>
                            <Input
                                id="edit-duration"
                                v-model="editForm.duration"
                                type="number"
                                min="1"
                                max="480"
                                placeholder="60"
                                required
                            />
                            <div v-if="editForm.errors.duration" class="text-sm text-red-600">
                                {{ editForm.errors.duration }}
                            </div>
                        </div>
                        <div class="space-y-2">
                            <Label for="edit-price">Price (R$)</Label>
                            <Input
                                id="edit-price"
                                v-model="editForm.price"
                                type="number"
                                step="0.01"
                                min="0"
                                placeholder="150.00"
                                required
                            />
                            <div v-if="editForm.errors.price" class="text-sm text-red-600">
                                {{ editForm.errors.price }}
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-end space-x-2">
                        <Button type="button" variant="outline" @click="isEditDialogOpen = false">
                            Cancel
                        </Button>
                        <Button type="submit" :disabled="editForm.processing">
                            Update Service
                        </Button>
                    </div>
                </form>
            </DialogContent>
        </Dialog>

        <!-- Delete Dialog -->
        <Dialog v-model:open="isDeleteDialogOpen">
            <DialogContent class="sm:max-w-[425px]">
                <DialogHeader>
                    <DialogTitle>Delete Service</DialogTitle>
                    <DialogDescription>
                        Are you sure you want to delete this service? This action cannot be undone.
                    </DialogDescription>
                </DialogHeader>
                <div v-if="selectedService" class="py-4">
                    <p class="font-medium">{{ selectedService.name }}</p>
                    <p class="text-sm text-muted-foreground">{{ formatPrice(selectedService.price) }} • {{ formatDuration(selectedService.duration) }}</p>
                </div>
                <div class="flex justify-end space-x-2">
                    <Button type="button" variant="outline" @click="isDeleteDialogOpen = false">
                        Cancel
                    </Button>
                    <Button type="button" variant="destructive" @click="deleteService">
                        Delete Service
                    </Button>
                </div>
            </DialogContent>
        </Dialog>
    </AppLayout>
</template>