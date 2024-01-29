import { NumberField, BooleanField, SimpleShowLayout, TextField, Show} from "react-admin";

export const StationShow = () => (
    <Show>
        <SimpleShowLayout>
            <TextField source="id" />
            <TextField source="name" />
            <NumberField source="longitude" />
            <NumberField source="latitude" />
            <TextField source="address" />
            <NumberField source="capacity" />
            <NumberField source="numberOfAvailableBicycles" />
            <BooleanField source="status" />
            <TextField source="city.id" />
            <TextField source="city.name" />
        </SimpleShowLayout>
    </Show>
);
