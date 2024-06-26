<?php
declare(strict_types=1);

namespace App\Form;

use App\Entity\Contact;
use Karser\Recaptcha3Bundle\Form\Recaptcha3Type;
use Karser\Recaptcha3Bundle\Validator\Constraints\Recaptcha3;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fullName', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'minlength' => '2',
                    'maxlength' => '50',
                    'placeholder' => 'Nom'
                ],
                'label' => 'Nom / Prenom',
                'label_attr' => [
                    'class' => 'form-label'
                ]
            ])
            ->add('email',EmailType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'minlength' => '2',
                    'maxlength' => '180',
                    'placeholder' => 'Mail'
                ],
                'label' => 'Adresse email',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ]
            ])
            ->add('subject', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Indiquer le sujet de votre demande'
                ],
                'label' => 'Sujet',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ]
            ])
            ->add('message', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Entrer votre message ici'
                ],
                'label' => 'Description',
                'label_attr' => [
                    'class' => 'form-label'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary mt-4'
                ],
                'label' => 'Envoyer'
            ])
            ->add('captcha', Recaptcha3Type::class, [
                'constraints' => new Recaptcha3(['message' => 'Il y\' a un problème avec votre reCAPTCHA. Essayez encore ou contacter le support en leur fournissant le code d\'erreur suivant : {{ errorCodes }}']),
                'action_name' => 'contact'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
