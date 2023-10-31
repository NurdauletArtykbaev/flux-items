<?php

namespace Nurdaulet\FluxItems\Filament\Resources\ItemResource\RelationManagers;

use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Intervention\Image\ImageManagerStatic;

class ImagesRelationManager extends RelationManager
{

    protected static string $relationship = 'images';
    protected static ?string $modelLabel = 'изображение';
    protected static ?string $pluralModelLabel = 'Изображения';

    protected static ?string $recordTitleAttribute = 'image';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\FileUpload::make('image')
                    ->image()
                    ->disk('s3')
                    ->directory('items')
                    ->label(trans('admin.image')),
                Forms\Components\Hidden::make('disk')->default('s3')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->width(150)
                    ->height(150)
                    ->disk('s3')
                    ->label(trans('admin.image')),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->mutateFormDataUsing(function (array $data): array {
                        $url = env('AWS_URL') . '/' . $data['image'];
                        $imageExtension = explode('.', $data['image']);
                        $imageExtension = end($imageExtension);

                        $file = file_get_contents($url);

                        $image = ImageManagerStatic::make($file)->encode($imageExtension, 90);

//                        if (!app()->isProduction()) {
                        $imageString = imagecreatefromstring($file);
                        $w = imagesx($imageString);
                        $h = imagesy($imageString);
                        $imageWebp = ImageManagerStatic::make($file);
                        if ($w >= 900 && $h >= 900){
                            $imageWebp = $imageWebp->resize(900, 900, function ($constraint) {
                                $constraint->aspectRatio();
                            });
                        }
                        $imageWebp = $imageWebp->encode('webp', 60);
//                        } else {
//                            $imageWebp = Image::make($file)->encode('webp', 80);
//                        }

                        $webpName = 'items/'.  time() . Str::uuid().'.webp';

                        $data['webp']  = $webpName;

                        Storage::disk('s3')->put($data['image'], $image);
                        Storage::disk('s3')->put($webpName, $imageWebp);
                        return $data;
                    })
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
