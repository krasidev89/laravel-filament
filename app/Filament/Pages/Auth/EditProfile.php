<?php

namespace App\Filament\Pages\Auth;

use Filament\Forms\Components\Component;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Pages\Auth\EditProfile as BaseEditProfile;

class EditProfile extends BaseEditProfile
{
    public static function isSimple(): bool
    {
        return false;
    }

    protected function getRolesFormComponent(): Component
    {
        return Select::make('roles')
            ->label(__('filament-panels::pages/auth/edit-profile.form.roles.label'))
            ->multiple()
            ->relationship('roles', 'name')
            ->preload();
    }

    /**
     * @return array<int | string, string | Form>
     */
    protected function getForms(): array
    {
        return [
            'form' => $this->form(
                $this->makeForm()->schema([
                    Section::make(__('filament-panels::pages/auth/edit-profile.form.section.general'))->schema([
                        Grid::make(['default' => 1, 'sm' => 2, 'md' => 3])->schema([
                            $this->getNameFormComponent(),
                            $this->getEmailFormComponent(),
                            $this->getRolesFormComponent(),
                        ]),
                    ]),
                    Section::make(__('filament-panels::pages/auth/edit-profile.form.section.update-password'))->schema([
                        Grid::make(['default' => 1, 'sm' => 2, 'md' => 3])->schema([
                            $this->getPasswordFormComponent(),
                            $this->getPasswordConfirmationFormComponent()
                                ->required(false)
                                ->visible(true)
                                ->rule('required', fn ($get) => !!$get('password'))
                                ->same('password'),

                        ]),
                    ]),
                ])
                ->operation('edit')
                ->model($this->getUser())
                ->statePath('data')
            ),
        ];
    }
}
