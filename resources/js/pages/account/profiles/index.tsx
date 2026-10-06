import { Form, Head } from '@inertiajs/react';
import { useState } from 'react';
import Heading from '@/components/heading';
import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import { destroy, store, update } from '@/routes/account/profiles';

type LearningProfile = {
    id: number;
    nickname: string;
    class_id: number | null;
    avatar_key: string | null;
    interests: string[] | null;
};

type SchoolClass = {
    id: number;
    name: string;
};

type Props = {
    profiles: LearningProfile[];
    classes: SchoolClass[];
    avatarKeys: string[];
    maxProfiles: number;
};

export default function ProfilesIndex({
    profiles,
    classes,
    avatarKeys,
    maxProfiles,
}: Props) {
    const [editingId, setEditingId] = useState<number | null>(null);
    const atCap = profiles.length >= maxProfiles;
    const classNameById = new Map(classes.map((c) => [c.id, c.name]));

    return (
        <>
            <Head title="Learning profiles" />

            <div className="space-y-6 p-4">
                <Heading
                    title="Learning profiles"
                    description="A nickname and class for each child. No child email, phone, school, date of birth or address is ever collected here."
                />

                <div className="space-y-4">
                    {profiles.map((profile) =>
                        editingId === profile.id ? (
                            <Form
                                key={profile.id}
                                {...update.form(profile.id)}
                                onSuccess={() => setEditingId(null)}
                                className="grid gap-3 rounded-lg border p-4"
                            >
                                {({ processing, errors }) => (
                                    <>
                                        <ProfileFields
                                            defaultNickname={profile.nickname}
                                            defaultClassId={profile.class_id}
                                            defaultAvatarKey={
                                                profile.avatar_key
                                            }
                                            classes={classes}
                                            avatarKeys={avatarKeys}
                                            errors={errors}
                                        />
                                        <div className="flex gap-2">
                                            <Button
                                                type="submit"
                                                disabled={processing}
                                            >
                                                {processing && <Spinner />}
                                                Save
                                            </Button>
                                            <Button
                                                type="button"
                                                variant="outline"
                                                onClick={() =>
                                                    setEditingId(null)
                                                }
                                            >
                                                Cancel
                                            </Button>
                                        </div>
                                    </>
                                )}
                            </Form>
                        ) : (
                            <div
                                key={profile.id}
                                className="flex items-center justify-between rounded-lg border p-4"
                            >
                                <div>
                                    <p className="font-medium">
                                        {profile.nickname}
                                    </p>
                                    <p className="text-sm text-muted-foreground">
                                        {profile.class_id
                                            ? (classNameById.get(
                                                  profile.class_id,
                                              ) ?? 'Class not set')
                                            : 'Class not set'}
                                        {profile.avatar_key
                                            ? ` · ${profile.avatar_key}`
                                            : ''}
                                    </p>
                                </div>
                                <div className="flex gap-2">
                                    <Button
                                        type="button"
                                        variant="outline"
                                        size="sm"
                                        onClick={() => setEditingId(profile.id)}
                                    >
                                        Edit
                                    </Button>
                                    <Form {...destroy.form(profile.id)}>
                                        {({ processing }) => (
                                            <Button
                                                type="submit"
                                                variant="outline"
                                                size="sm"
                                                disabled={processing}
                                            >
                                                Remove
                                            </Button>
                                        )}
                                    </Form>
                                </div>
                            </div>
                        ),
                    )}

                    {profiles.length === 0 && (
                        <p className="text-sm text-muted-foreground">
                            No learning profiles yet.
                        </p>
                    )}
                </div>

                {atCap ? (
                    <p className="text-sm text-muted-foreground">
                        You have reached the maximum of {maxProfiles} learning
                        profiles.
                    </p>
                ) : (
                    <Form
                        {...store.form()}
                        resetOnSuccess
                        className="grid gap-3 rounded-lg border p-4"
                    >
                        {({ processing, errors }) => (
                            <>
                                <h2 className="font-medium">Add child</h2>
                                <ProfileFields
                                    classes={classes}
                                    avatarKeys={avatarKeys}
                                    errors={errors}
                                />
                                <div>
                                    <Button
                                        type="submit"
                                        disabled={processing}
                                        data-test="add-profile-button"
                                    >
                                        {processing && <Spinner />}
                                        Add child
                                    </Button>
                                </div>
                            </>
                        )}
                    </Form>
                )}
            </div>
        </>
    );
}

function ProfileFields({
    defaultNickname,
    defaultClassId,
    defaultAvatarKey,
    classes,
    avatarKeys,
    errors,
}: {
    defaultNickname?: string;
    defaultClassId?: number | null;
    defaultAvatarKey?: string | null;
    classes: SchoolClass[];
    avatarKeys: string[];
    errors: Partial<Record<string, string>>;
}) {
    return (
        <>
            <div className="grid gap-2">
                <Label htmlFor="nickname">Nickname</Label>
                <Input
                    id="nickname"
                    name="nickname"
                    defaultValue={defaultNickname}
                    required
                    maxLength={40}
                    placeholder="Not a legal name"
                />
                <InputError message={errors.nickname} />
            </div>

            <div className="grid gap-2">
                <Label htmlFor="class_id">Class</Label>
                <select
                    id="class_id"
                    name="class_id"
                    defaultValue={defaultClassId ?? ''}
                    className="h-9 rounded-md border border-input bg-transparent px-3 text-sm"
                >
                    <option value="">Not set</option>
                    {classes.map((schoolClass) => (
                        <option key={schoolClass.id} value={schoolClass.id}>
                            {schoolClass.name}
                        </option>
                    ))}
                </select>
                <InputError message={errors.class_id} />
            </div>

            <div className="grid gap-2">
                <Label htmlFor="avatar_key">Avatar</Label>
                <select
                    id="avatar_key"
                    name="avatar_key"
                    defaultValue={defaultAvatarKey ?? ''}
                    className="h-9 rounded-md border border-input bg-transparent px-3 text-sm"
                >
                    <option value="">No avatar</option>
                    {avatarKeys.map((key) => (
                        <option key={key} value={key}>
                            {key}
                        </option>
                    ))}
                </select>
                <InputError message={errors.avatar_key} />
            </div>
        </>
    );
}
