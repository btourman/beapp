import {BooleanField, SimpleShowLayout, NumberField, Show, TextField} from "react-admin";

export const CityShow = () => (
    <Show>
        <SimpleShowLayout>
            <TextField source="name" />
            <NumberField source="longitude" />
            <NumberField source="latitude" />
            <BooleanField source="status" />
        </SimpleShowLayout>
    </Show>
);
