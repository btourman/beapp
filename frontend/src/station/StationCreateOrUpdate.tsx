import PropTypes from 'prop-types';
import {BooleanInput, Create, Edit, NumberInput, SimpleForm, TextInput, useCreate, useUpdate, useNotify} from "react-admin";

export const StationCreateOrUpdate = ({isUpdating = false}) => {
    const notify = useNotify();
    const [create] = useCreate();
    const [update] = useUpdate();
    const submitStation = (data: any) => {
        data.city = data.city.id;
        if(isUpdating) {
            return update("station", { id: data.id, data })
                .then(() => notify(`Element updated`, { type: 'success' }));
        } else {
            return create("station", { data })
                .then(() => notify(`Element created`, { type: 'success' }));
        }
    }

    const form = (
        <SimpleForm onSubmit={submitStation}>
            <TextInput source="name" required/>
            <NumberInput source="longitude" required/>
            <NumberInput source="latitude" required/>
            <TextInput source="address" required/>
            <NumberInput source="capacity" min={0} required/>
            <NumberInput source="numberOfAvailableBicycles" min={0} required/>
            <BooleanInput source="status" />
            <TextInput source="city.id" required/>
        </SimpleForm>
    )

    if(isUpdating) {
        return (
            <Edit>{form}</Edit>
        );
    }
    return (
        <Create>{form}</Create>
    );
}

StationCreateOrUpdate.propTypes = {
    isUpdating: PropTypes.bool
};
