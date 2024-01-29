import {BooleanInput, Create, Edit, NumberInput, SimpleForm, TextInput} from "react-admin";
import PropTypes from "prop-types";

export const CityCreateOrUpdate = ({isUpdating = false}) => {
    const form = (
        <SimpleForm>
        <TextInput source="name" required/>
        <NumberInput source="longitude" required/>
        <NumberInput source="latitude" required/>
        <BooleanInput source="status" />
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

CityCreateOrUpdate.propTypes = {
    isUpdating: PropTypes.bool
};
